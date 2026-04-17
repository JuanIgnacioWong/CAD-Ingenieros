<?php

declare(strict_types=1);

if ($argc !== 7) {
    fwrite(STDERR, "Uso: php scripts/wordpress-search-replace.php <host> <db> <user> <pass> <old-url> <new-url>\n");
    exit(1);
}

[$script, $host, $database, $user, $password, $oldUrl, $newUrl] = $argv;

$mysqli = mysqli_init();

if (!$mysqli) {
    fwrite(STDERR, "No se pudo inicializar mysqli.\n");
    exit(1);
}

$mysqli->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);

if (!$mysqli->real_connect($host, $user, $password, $database)) {
    fwrite(STDERR, "No se pudo conectar a MySQL: " . mysqli_connect_error() . "\n");
    exit(1);
}

$mysqli->set_charset('utf8mb4');

$columnQuery = <<<SQL
SELECT
    TABLE_NAME,
    COLUMN_NAME
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = ?
  AND DATA_TYPE IN ('char', 'varchar', 'tinytext', 'text', 'mediumtext', 'longtext', 'json')
ORDER BY TABLE_NAME, ORDINAL_POSITION
SQL;

$columnStmt = $mysqli->prepare($columnQuery);
$columnStmt->bind_param('s', $database);
$columnStmt->execute();
$columnResult = $columnStmt->get_result();

$tableColumns = [];

while ($row = $columnResult->fetch_assoc()) {
    $tableColumns[$row['TABLE_NAME']][] = $row['COLUMN_NAME'];
}

$columnStmt->close();

$primaryKeyQuery = <<<SQL
SELECT
    TABLE_NAME,
    COLUMN_NAME
FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = ?
  AND CONSTRAINT_NAME = 'PRIMARY'
ORDER BY TABLE_NAME, ORDINAL_POSITION
SQL;

$primaryKeyStmt = $mysqli->prepare($primaryKeyQuery);
$primaryKeyStmt->bind_param('s', $database);
$primaryKeyStmt->execute();
$primaryKeyResult = $primaryKeyStmt->get_result();

$primaryKeys = [];

while ($row = $primaryKeyResult->fetch_assoc()) {
    $primaryKeys[$row['TABLE_NAME']][] = $row['COLUMN_NAME'];
}

$primaryKeyStmt->close();

$updatedRows = 0;
$updatedFields = 0;

foreach ($tableColumns as $table => $textColumns) {
    $keyColumns = $primaryKeys[$table] ?? [];

    if ($keyColumns === []) {
        fwrite(STDOUT, "Saltando {$table}: no tiene PRIMARY KEY.\n");
        continue;
    }

    $selectColumns = array_merge($keyColumns, $textColumns);
    $selectList = implode(', ', array_map(static fn(string $column): string => '`' . $column . '`', $selectColumns));
    $selectSql = 'SELECT ' . $selectList . ' FROM `' . $table . '`';
    $result = $mysqli->query($selectSql);

    if (!$result) {
        fwrite(STDERR, "No se pudo leer {$table}: {$mysqli->error}\n");
        exit(1);
    }

    while ($row = $result->fetch_assoc()) {
        $changes = [];

        foreach ($textColumns as $column) {
            $value = $row[$column];

            if (!is_string($value) || strpos($value, $oldUrl) === false) {
                continue;
            }

            $newValue = replaceValue($value, $oldUrl, $newUrl);

            if ($newValue === $value) {
                continue;
            }

            $changes[$column] = $newValue;
        }

        if ($changes === []) {
            continue;
        }

        $setParts = [];
        $types = '';
        $params = [];

        foreach ($changes as $column => $value) {
            $setParts[] = '`' . $column . '` = ?';
            $types .= 's';
            $params[] = $value;
        }

        $whereParts = [];

        foreach ($keyColumns as $column) {
            $whereParts[] = '`' . $column . '` = ?';
            $types .= is_int($row[$column]) ? 'i' : 's';
            $params[] = $row[$column];
        }

        $updateSql = 'UPDATE `' . $table . '` SET ' . implode(', ', $setParts) . ' WHERE ' . implode(' AND ', $whereParts);
        $updateStmt = $mysqli->prepare($updateSql);

        if (!$updateStmt) {
            fwrite(STDERR, "No se pudo preparar UPDATE para {$table}: {$mysqli->error}\n");
            exit(1);
        }

        $updateStmt->bind_param($types, ...$params);

        if (!$updateStmt->execute()) {
            fwrite(STDERR, "No se pudo actualizar {$table}: {$updateStmt->error}\n");
            $updateStmt->close();
            exit(1);
        }

        $updatedRows++;
        $updatedFields += count($changes);
        $updateStmt->close();
    }

    $result->close();
}

fwrite(STDOUT, "Filas actualizadas: {$updatedRows}\n");
fwrite(STDOUT, "Campos actualizados: {$updatedFields}\n");

function replaceValue(string $value, string $oldUrl, string $newUrl): string
{
    $trimmed = trim($value);

    if (isSerialized($trimmed)) {
        $unserialized = @unserialize($trimmed, ['allowed_classes' => true]);

        if ($unserialized !== false || $trimmed === 'b:0;') {
            $replaced = deepReplace($unserialized, $oldUrl, $newUrl);
            return serialize($replaced);
        }
    }

    return str_replace($oldUrl, $newUrl, $value);
}

function deepReplace(mixed $value, string $oldUrl, string $newUrl): mixed
{
    if (is_string($value)) {
        return str_replace($oldUrl, $newUrl, $value);
    }

    if (is_array($value)) {
        foreach ($value as $key => $item) {
            $value[$key] = deepReplace($item, $oldUrl, $newUrl);
        }

        return $value;
    }

    if (is_object($value)) {
        foreach ($value as $property => $item) {
            $value->{$property} = deepReplace($item, $oldUrl, $newUrl);
        }

        return $value;
    }

    return $value;
}

function isSerialized(string $data, bool $strict = true): bool
{
    if ($data === 'N;') {
        return true;
    }

    if (strlen($data) < 4) {
        return false;
    }

    if ($data[1] !== ':') {
        return false;
    }

    if ($strict) {
        $last = substr($data, -1);

        if ($last !== ';' && $last !== '}') {
            return false;
        }
    } else {
        $semicolon = strpos($data, ';');
        $brace = strpos($data, '}');

        if ($semicolon === false && $brace === false) {
            return false;
        }

        if ($semicolon !== false && $semicolon < 3) {
            return false;
        }

        if ($brace !== false && $brace < 4) {
            return false;
        }
    }

    $token = $data[0];

    switch ($token) {
        case 's':
            if ($strict && substr($data, -2, 1) !== '"') {
                return false;
            }

            return preg_match('/^s:[0-9]+:.*;$/s', $data) === 1;
        case 'a':
        case 'O':
        case 'E':
            return preg_match('/^' . $token . ':[0-9]+:.*$/s', $data) === 1;
        case 'b':
        case 'i':
        case 'd':
            return preg_match('/^' . $token . ':[0-9.E+-]+;$/', $data) === 1;
    }

    return false;
}
