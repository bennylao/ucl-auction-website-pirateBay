<?php
// This is the script that initialise the database
// For internal development and use only

require_once("config_database.php");

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "auctionDataBase";

// Create connection
$conn = mysqli_connect($servername, $username, $password);
// Check connection
if (!$conn) {
    $password = '';
    $conn = mysqli_connect($servername, $username, $password);
    // if connection fails again, alert the user to check if the server is running
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
}
// Drop the database if it exists
$sql = "DROP DATABASE IF EXISTS auctionDataBase";
mysqli_query($conn, $sql);

// Create database
$sql = "CREATE DATABASE auctionDataBase";
if (mysqli_query($conn, $sql)) {
    echo "Database created successfully. ";
} else {
    echo "Error creating database: " . mysqli_error($conn);
}
mysqli_close($conn);

$conn = connect_to_database();

// Create the admin table
$sql = "CREATE TABLE accountTypes (
typeId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
accountType VARCHAR(255) NOT NULL)";

if (mysqli_query($conn, $sql)) {
    echo "Admin Table created successfully. ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create admin records
$sql = "INSERT INTO accountTypes (typeId, accountType)
        VALUES (1, 'buyer'),
               (2, 'seller'),
               (3, 'buyerseller'),
               (4, 'admin');";

if (mysqli_query($conn, $sql)) {
    echo "Account Type records created successfully. \n";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create the first table users
$sql = "CREATE TABLE users (
userId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
accountType INT NOT NULL,
userName VARCHAR(255) NOT NULL,
password VARCHAR(255) NOT NULL,
email VARCHAR(255) NOT NULL,
firstName VARCHAR(255) NOT NULL,
lastName VARCHAR(255) NOT NULL,
address VARCHAR(255),
createDate date,
FOREIGN KEY (accountType) REFERENCES accountTypes(typeId));";

if (mysqli_query($conn, $sql)) {
    echo "Table User created successfully. ";
} else {
    echo "Error creating table User: " . mysqli_error($conn);
}

// Create records for users
$sql = "INSERT INTO users (accountType, userName, password, email, firstName, lastName, address, createDate)
        VALUES  (4, 'admin', '$2y$12\$aUEuvjZcMK6C061pVzXLvuVIuhb/X5r85.JXxXymSxDKLuLAfeigi', 'admin', 'admin', 'admin', 'admin', '2000-01-01'),
                (1, 'buyer1', '$2y$12\$PDMuIe4/89OT6e/qI0Y0GOJpOpG7jpf1D3OsYqHOA8BJINthe3HWi','buyer1','Buyer1FN','Buyer1LN','123 King Cross','2020-01-01'),
                (1, 'buyer2', '$2y$12$0TEsX/Z04.JGglspufc2SOUteKvVe2RxcVfAZ65dNXNTwj5573MYi','buyer2','Buyer2FN','Buyer2LN','123 Tottenham Court Road','2020-04-09'),
                (2, 'seller1', '$2y$12\$wDW0asKX3.JqO2qekoJjg.pw9aSkUJ457OGgcnftyKYKF6RVVk7gW', 'seller1', 'Seller1FN', 'Seller1LN', '20 Tottenham Court Road', '2020-06-11'),
                (2, 'seller2', '$2y$12$4Ci4LUOckcr86M3jU3zwe.SaoV4Al.nyeSkhA2llAZ5imzaVUtC1a', 'seller2', 'Seller2FN', 'Seller2LN', '30 Oxford Street', '2020-07-13'),
                (3, 'buyerseller1','$2y$12\$kRpbUPnJrfiDkCNoY7C.gepugZNt4YvzycA3z99dTlX/sQCk2/It.', 'buyerseller1', 'BuyerSeller1FN', 'BuyerSeller1LN', '10 Gower Street', '2020-08-01'),
                (3, 'buyerseller2', '$2y$12$8ut3fKcWRZOzqUxW.5pn6.1Ibrirymbnyu2zOFb7K9cfpb7CxHFn.', 'buyerseller2', 'BuyerSeller2FN', 'BuyerSeller2LN', '20 Gower Street', '2020-08-03'),
                (1, 'user0', '$2y$10$4e6ITty1JFx53RnOPaVXH.orr9GSNL6nsg1h2z0CAHxzclN9rFhtG','email','fn','ln','addr','2021-06-05'), 
                (2, 'user1', '$2y$10\$Cej7YR5.IYEpd93WwBWQyO/tgFqn.QDC6La5oiwq.LAkX9R78RHMe','email1','fn1','ln1','addr1','2021-06-05'),
                (3, 'user2', '$2y$10\$xkBU7pYHLKP6ETnXp9R/eOsrHsEmORfTYvq5bqtzkx1RpO4ghDe5y','email2','fn2','ln2','addr2','2021-06-07'),
                (2, 'victor', '$2y$10\$x/oH2Gy1hdAHcoOoO4YNtOLJrCPW8PE25Mmi1tuTiFJ2MKxBdaYYq','victor2263@gmail.com','victor','chan','123 Oxford Street','2023-10-31'),
                (1, 'buyer_demo', '$2y$10\$YeiwMEskxv4xs2mB.OAMqe.nXvKq/oI2/GKVo/dhNkT6aDIUOm2gy', 'buyer_demo@ucl.ac.uk', 'james', 'smith', '123 road', '2023-10-31'),
                (3, 'buyerseller_demo', '$2y$10\$YeiwMEskxv4xs2mB.OAMqe.nXvKq/oI2/GKVo/dhNkT6aDIUOm2gy','buyerseller_demo@ucl.ac.uk', 'jaden', 'smith', '123 road', '2023-10-31');";

if (mysqli_query($conn, $sql)) {
    echo "New User records created successfully. ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}



// Create the category table
$sql = "CREATE TABLE categories (
cateId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
category VARCHAR(255) NOT NULL)";

if (mysqli_query($conn, $sql)) {
    echo "Categories Table created successfully. ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create category options for users
$sql = "INSERT INTO categories (cateId, category)
        VALUES (1, 'Art and Collectibles'),
               (2, 'Antiques'),
               (3, 'Automobiles and Vehicles'),
               (4, 'Jewelry and Watches'),
               (5, 'Electronics and Technology'),
               (6, 'Fashion and Apparel'),
               (7, 'Sports and Memorabilia'),
               (8, 'Wine and Spirits'),
               (9, 'Furniture and Home Decor'),
               (10, 'Real Estate'),
               (11, 'Others');";

if (mysqli_query($conn, $sql)) {
    echo "Categories options created successfully. ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}



// Create the table for
$sql = "CREATE TABLE conditions (
    conditionId INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    condDescript VARCHAR(255) NOT NULL
)";

if (mysqli_query($conn, $sql)) {
    echo "Table conditions created successfully. ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create records for the conditions table
$sql = "INSERT INTO conditions (conditionId, condDescript)
VALUES (1, 'Brand new'),
       (2, 'Like new'),
       (3, 'Very good'),
       (4, 'Good'),
       (5, 'Acceptable'),
       (6, 'Certified Refurbished'),
       (7, 'For Parts or Not Working')";

if (mysqli_query($conn, $sql)) {
    echo "New conditions records created successfully. ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create the second table items
$sql = "CREATE TABLE items (
itemId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
itemTitle VARCHAR(255) NOT NULL,
category INT NOT NULL,
conditions INT NOT NULL,
description VARCHAR(255) NOT NULL,
sellerId INT NOT NULL,
ownerId INT,
startingPrice DECIMAL NOT NULL,
startDateTime DATETIME NOT NULL,
endDateTime DATETIME NOT NULL,
brand VARCHAR(255),
reservedPrice DECIMAL,
isRead BIT,
FOREIGN KEY (sellerId) REFERENCES users(userId),
FOREIGN KEY (ownerId) REFERENCES users(userId),
FOREIGN KEY (conditions) REFERENCES conditions(conditionId) ON DELETE RESTRICT,
FOREIGN KEY (category) REFERENCES categories(cateId) ON DELETE RESTRICT
)";

if (mysqli_query($conn, $sql)) {
    echo "Table Item created successfully. ";
} else {
    echo "Error creating table Item: " . mysqli_error($conn);
}

// Create records for items
$sql = "INSERT INTO items (itemId, itemTitle, category, conditions, description, sellerId, ownerId,
                   startingPrice, startDateTime, endDateTime, brand, reservedPrice, isRead)
        VALUES  (1, 'Expired Item 1', 11, 1, 'This is an expired item1', 6, 6, 2, '2023-05-20 00:00:00','2023-10-30 00:00:00', 'expiry1', 6.7,NULL),
                (2, 'Expired Item 2', 11, 3, 'This is an expired item2', 6, 6, 2, '2023-07-30 00:00:00','2023-11-02 00:00:00', 'expiry2', 6.7,NULL),
                (3, 'An apple', 11, 1, 'golden apple', 6, 6, 2, '2023-09-30 00:00:00', '2024-02-11 11:00:00', 'apple banana', 6.7,NULL),
                (4, 'Sony A7m3 with 16-35 f2.8', 5, 1, 'Sony A7m3 with 16-35 f2.8 in good condition.', 6, 6, 500, '2023-10-01 05:00:00', '2024-03-31 23:00:00', 'Sony', 6.7,NULL),
                (5, 'MacBook Pro 16 inch M3 Max', 5, 1, 'A brand new Macbook Pro 16 inch with M3 Max', 4, 4, 1000, '2023-10-01 18:30:00', '2024-04-22 23:00:00', 'Apple', 6.7,NULL),
                (6, 'iPhone 17 Pro', 5, 1, 'Can play Fortnite', 4, 4, 1000, '2023-10-03 15:30:00', '2024-01-20 01:00:00', 'Apple', 6.7,NULL),
                (7, 'Samsung Galaxy S87 Ultra', 5, 4, 'Can play GTAVI', 4, 4, 1000, '2023-10-05 20:30:00', '2024-01-03 17:30:00', 'Samsung', 6.7,NULL),
                (8, 'My Brain', 11, 7, 'This is my brain', 5, 5, 1000, '2023-10-05 22:30:00', '2024-03-01 00:00:00', 'Me', 6.7,NULL),
                (9, 'iMac', 5, 6, 'Intel Mac not good', 4, 4, 5000, '2023-10-06 00:30:00', '2024-02-01 00:00:00', 'Apple', 6.7,NULL),
                (10, 'Apple Vision Pro', 5, 1, 'Waste of your money', 5, 5, 800, '2023-10-06 12:30:00', '2024-01-03 00:00:00', 'Apple', 6.7,NULL),
                (11, 'UCL Premium Study Space', 11, 1, 'overpriced honestly', 6, 6, 10, '2023-10-07 12:30:00', '2024-02-04 00:00:00', 'UCL', 6.7,NULL),
                (12, 'iPad Pro 12.9', 5, 2, 'just buy macbook', 6, 6, 400, '2023-10-07 21:30:00', '2024-01-30 00:00:00', 'Apple', 6.7,NULL),
                (13, 'AirPods', 5, 1, 'It is not too bad', 4, 4, 50, '2023-10-08 00:30:00', '2024-02-10 00:00:00', 'Apple', 6.7,NULL),
                (14, 'Shoulder Massager', 5, 1, 'It is not too bad', 4, 10, 50, '2023-10-08 00:30:00', '2023-11-20 00:00:00', 'Banana', 6.7,NULL),
                (15, 'a mug', 11, 1, 'It is not too bad', 10, 10, 50, '2023-10-08 00:30:00', '2024-11-15 00:00:00', 'Banana', 6.7,NULL)";
;

if (mysqli_query($conn, $sql)) {
    echo "New Item records created successfully. ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create the bid history table
$sql = "CREATE TABLE bidHistory (
bidId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
itemId INT NOT NULL,
userId INT NOT NULL,
bidPrice DECIMAL(12, 2) NOT NULL ,
bidDateTime DATETIME NOT NULL,
isRead BIT,
FOREIGN KEY (itemId) REFERENCES items(itemId) ON DELETE CASCADE,
FOREIGN KEY (userId) REFERENCES users(userId)
)";

if (mysqli_query($conn, $sql)) {
    echo "Bid history table created successfully. ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create records for bidHistory
$sql = "INSERT INTO bidHistory(bidId, itemId, userId, bidPrice, bidDateTime, isRead)
VALUES (1, 5, 6, 1020, '2020-10-10 12:30:00',NULL),
       (2, 5, 3, 1050, '2022-12-12 15:30:00',NULL),
       (3, 3, 7, 5, '2023-10-01 19:30:00',NULL),
       (4, 3, 10, 7.98, '2023-10-01 18:30:00',NULL),
       (5, 5, 6, 1100, '2023-10-02 15:30:00',NULL),
       (6, 5, 3, 1200, '2023-10-02 16:30:00',NULL),
       (7, 5, 6, 1300, '2023-10-02 17:30:00',NULL),
       (8, 11, 2, 12, '2023-10-02 17:30:00',NULL),
       (9, 13, 6, 55, '2023-10-02 17:35:30',NULL),
       (10, 11, 3, 57, '2023-10-02 17:35:39',NULL),
       (11, 11, 7, 60, '2023-10-03 10:00:00',NULL),
       (12, 14, 10, 60, '2023-11-14 00:00:00',NULL),
       (13, 14, 6, 70, '2023-11-15 00:00:00',NULL),
       (14, 14, 10, 80, '2023-11-16 00:00:00',NULL),
       (15, 14, 6, 90, '2023-11-17 00:00:00',NULL),
       (16, 14, 10, 100, '2023-11-18 00:00:00',NULL)";

if (mysqli_query($conn, $sql)) {
    echo "New bid history records created successfully. ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create the wishList table
$sql = "CREATE TABLE wishList (
    itemId INT NOT NULL,
    userId INT NOT NULL,
    FOREIGN KEY (itemId) REFERENCES items(itemId) ON DELETE CASCADE,
    FOREIGN KEY (userId) REFERENCES users(userId)
)";

if (mysqli_query($conn, $sql)) {
    echo "Table wishlist created successfully. ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create the records for the wishList table
$sql = "INSERT INTO wishList (itemId, userId)
VALUES (5, 2),
       (5, 3),
       (5, 6),
       (5, 7),
       (5, 8),
       (1, 10),
       (2, 10),
       (3, 10),
       (4, 10),
       (5, 10),
       (6, 10),
       (7, 10)";

if (mysqli_query($conn, $sql)) {
    echo "New wishlist records created successfully. ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

$sql = "CREATE TABLE images(
    imageID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    imagePath VARCHAR(255) NOT NULL ,
    itemID INT NOT NULL,
    FOREIGN KEY (itemID) REFERENCES items(itemId) ON DELETE CASCADE 
)";

if (mysqli_query($conn, $sql)) {
    echo "Table images created successfully. ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

$sql = "INSERT INTO images (imagePath, itemID)
VALUES 
('auction_image/mbp16-spaceblack-gallery1-202310.png', 5),
('auction_image/mbp16-spaceblack-gallery2-202310_GEO_GB.jpeg', 5),
('auction_image/mbp16-spaceblack-gallery3-202310.jpeg', 5),
('auction_image/mbp16-spaceblack-gallery4-202310.jpeg', 5),
('auction_image/mbp16-spaceblack-gallery5-202310.jpeg', 5),
('auction_image/mbp16-spaceblack-gallery6-202310.jpeg', 5),
('auction_image/changing_place.jpg', 11),
('auction_image/Golden_Apple_JE2_BE2.png', 3),
('auction_image/Primary_Image-2.png', 4),
('auction_image/71R6LTyMphL._AC_UF1000,1000_QL80_.jpg', 4),
('auction_image/iphone1.1697206388.8248.jpg', 6),
('auction_image/uk-galaxy-s22-ultra-s908-sm-s908bzkgeub-530846955.png', 7),
('auction_image/51YshU3zBxL._AC_UF894,1000_QL80_.jpg',8),
('auction_image/imac-24-no-id-blue-selection-hero-202310.jpeg',9),
('auction_image/sola-adult-mask-snorkel-set-8877-1-p.jpg', 10),
('auction_image/ipad-pro-finish-select-202212-12-9inch-space-gray-wifi_FMT_WHH.jpeg', 12),
('auction_image/MV7N2.jpeg', 13),
('auction_image/3902fb565f0590c79cbcdf062663d63b.jpeg', 14),
('auction_image/444Iittala_AK.jpeg', 15)
";

if (mysqli_query($conn, $sql)) {
    echo "New images records created successfully. ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}


mysqli_close($conn);
?>
