CREATE TABLE `restaurant_table` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tableNumber INT,
    numberOfPeople INT,
    isReserved TINYINT(1),
    isOutside TINYINT(1)
);
