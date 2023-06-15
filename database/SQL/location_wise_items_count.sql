select b.id as location_id, b.name as location_name, a.item_id, c.name as item_name,
sum(a.quantity) as total_qty
from
item_inventories a, item_locations b, items c
where
a.location_id = b.id
and
a.item_id = c.id
group by a.location_id, a.item_id
order by a.location_id asc, a.item_id asc