SET @sql = NULL;

SELECT
    GROUP_CONCAT(DISTINCT
        CONCAT(
            'SUM(CASE WHEN a.location_id = ', location_id, ' THEN a.quantity ELSE 0 END) AS \'', location_id, '\''
        )
    ) INTO @sql
FROM item_inventories;

SET @sql = CONCAT(
    'SELECT b.id AS location_id, b.name AS location_name, a.item_id, c.name AS item_name, ', @sql, '
    FROM item_inventories a
    JOIN item_locations b ON a.location_id = b.id
    JOIN items c ON a.item_id = c.id
    GROUP BY a.location_id, a.item_id
    ORDER BY a.location_id asc, a.item_id asc'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
