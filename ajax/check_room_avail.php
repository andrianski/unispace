<?php

-- Заявка за проверка на наличност за конкретен ден
SELECT * FROM reservations 
WHERE classroom_id = :classroom_id 
  AND slot_id = :slot_id 
  AND date = :date
  AND status != 'cancelled';

-- За повтарящи се събития, проверка за всички дати в диапазона
WITH RECURSIVE dates AS (
  SELECT :start_date AS date
  UNION ALL
  SELECT DATE_ADD(date, INTERVAL :interval DAY)
  FROM dates 
  WHERE date < :end_date
)
SELECT d.date FROM dates d
JOIN reservations r ON d.date = r.date
WHERE r.classroom_id = :classroom_id
  AND r.slot_id = :slot_id
  AND r.status != 'cancelled';

?>