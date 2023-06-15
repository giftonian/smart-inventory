select b.id as location_id, b.name as location_name, a.item_id, c.name as item_name, 
SUM(  CASE WHEN a.location_id = 1 THEN a.quantity ELSE 0 END) AS '1', 
SUM(  CASE WHEN a.location_id = 2 THEN a.quantity ELSE 0 END)  AS '2',
SUM(  CASE WHEN a.location_id = 3 THEN a.quantity ELSE 0 END)   AS '3' 
from item_inventories a, item_locations b, items c 
where 
a.location_id = b.id 
and
a.item_id = c.id
group by a.location_id, a.item_id
order by b.name;
