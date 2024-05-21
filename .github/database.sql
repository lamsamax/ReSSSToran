/*CREATE DATABASE project;*/
USE project;

CREATE TABLE CATEGORY (
                          categoryID INT PRIMARY KEY,
                          name VARCHAR(255),
                          description TEXT
);
CREATE TABLE ITEM (
                      itemID INT PRIMARY KEY,
                      name VARCHAR(255),
                      price FLOAT,
                      description TEXT,
                      imageUrl VARCHAR(255),
                      categoryID INT,
                      available TINYINT(1),
                      FOREIGN KEY (categoryID) REFERENCES CATEGORY(categoryID)
);

CREATE TABLE STAFF (
                       staffID INT PRIMARY KEY,
                       name VARCHAR(255),
                       surname VARCHAR(255),
                       dob DATETIME,
                       mail VARCHAR(255),
                       password VARCHAR(255)
);

CREATE TABLE CUSTOMER (
                          userID INT PRIMARY KEY,
                          name VARCHAR(255),
                          surname VARCHAR(255),
                          dob DATETIME,
                          role VARCHAR(255),
                          mail VARCHAR(255),
                          password VARCHAR(255),
                          isAdmin TINYINT(1)
);

CREATE TABLE ROOM (
                      roomID INT PRIMARY KEY,
                      roomNumber INT,
                      floor INT
);

CREATE TABLE ORDERS (
                       orderID INT PRIMARY KEY,
                       status INT,
                       orderDate DATETIME,
                       grade INT,
                       review TEXT,
                       customerID INT,
                       sdeliverID INT,
                       stakeID INT,
                       FOREIGN KEY (customerID) REFERENCES CUSTOMER(userID),
                       FOREIGN KEY (sdeliverID) REFERENCES STAFF(staffID),
                       FOREIGN KEY (stakeID) REFERENCES STAFF(staffID)
);
CREATE TABLE ORDER_ITEM (
                            orderitemID INT PRIMARY KEY,
                            orderID INT,
                            itemID INT,
                            quantity INT,
                            FOREIGN KEY (orderID) REFERENCES ORDERS(orderID),
                            FOREIGN KEY (itemID) REFERENCES ITEM(itemID)
);


CREATE TABLE DELIVERY_ROOM (
                               deliveryroomID INT PRIMARY KEY,
                               roomID INT,
                               orderID INT,
                               FOREIGN KEY (roomID) REFERENCES ROOM(roomID),
                               FOREIGN KEY (orderID) REFERENCES ORDERS(orderID)
);

INSERT INTO CATEGORY (categoryID, name, description) VALUES (1, 'Breakfast', 'Doručak');
INSERT INTO CATEGORY (categoryID, name, description) VALUES (2, 'Sandwiches', 'Sendviči');
INSERT INTO CATEGORY (categoryID, name, description) VALUES (3, 'Main Courses', 'Glavna jela');
INSERT INTO CATEGORY (categoryID, name, description) VALUES (4, 'Salads', 'Salate');
INSERT INTO CATEGORY (categoryID, name, description) VALUES (5, 'Soups', 'Supe');
INSERT INTO CATEGORY (categoryID, name, description) VALUES (6, 'Pizzas', 'Pizze');
INSERT INTO CATEGORY (categoryID, name, description) VALUES (7, 'Daily Offers', 'Dnevne ponude');
INSERT INTO CATEGORY (categoryID, name, description) VALUES (8, 'Cold Beverages', 'Hladni napici');
INSERT INTO CATEGORY (categoryID, name, description) VALUES (9, 'Hot Beverages', 'Topli napici');


INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (1, 'Toast', 'Toasted bread with cheese and ham', 2.5, 'images/toast.jpg', 1, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (2, 'Omelette', 'Omelette with cheese', 5, 'images/omelette.jpg', 1, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (3, 'Fried Dough', 'Fried dough with greek yogurt and dried meat', 5.5, 'images/fried-dough.jpg', 1, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (4, 'Bosnian Sandwich', 'Dried meat, yogurt, salad, and cheese in a burger bun', 4, 'images/bosnian-sandwich.jpg', 2, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (5, 'Chicken Burger', 'Chicken fillet 100gr, mayo, salad, and cheese in a burger bun', 4, 'images/chicken-burger.jpg', 2, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (6, 'Hamburger', 'Beef 100gr, mayo, and salad in a burger bun', 4.5, 'images/hamburger.jpg', 2, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (7, 'Cheeseburger', 'Beef 100gr, mayo, salad, and cheese in a burger bun', 5, 'images/cheeseburger.jpg', 2, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (8, 'Chicken with White Sauce and Rice', 'Chicken fillet 100gr, white sauce, and rice', 7.5, 'images/chicken-with-rice.jpg', 3, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (9, 'Fried Chicken and Fries', 'Chicken fillet 100gr, mayo and fries', 7.5, 'images/fried-chicken.jpg', 3, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (10, 'Chicken and Vegetables', 'Chicken fillet 100gr and vegetables', 7.5, 'images/chicken-with-vegetables.jpg', 3, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (11, 'Bosnian Maslenica', 'Chicken fillet 100gr, white sauce, in pizza dough', 7, 'images/maslanica.jpg', 3, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (12, 'Green Salad', 'Green salad, cucumber, onion, tomato', 4, 'images/green-salad.jpg', 4, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (13, 'Chicken Salad', 'Green salad, cucumber, onion, tomato, chicken', 4.5, 'images/chicken-salad.jpg', 4, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (14, 'Caesar Salad', 'Green salad, cucumber, onion, tomato, chicken, parmesan', 4.5, 'images/caesar-salad.jpg', 4, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (15, 'Trout Salad', 'Green salad, cucumber, onion, tomato, trout', 5, 'images/trout-salad.jpg', 4, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (16, 'Beys Soup', 'Chicken drums, celeriac, carrot, egg, sour cream', 4, 'images/beys-soup.jpg', 5, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (17, 'Chicken Noodle Soup', 'Chicken, celeriac, carrot, soup noodles', 4, 'images/chicken-noodle-soup.jpg', 5, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (18, 'Vegetable Soup', 'Tomato, celeriac, carrot, garlic', 4, 'images/vegetable-soup.jpg', 5, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (19, 'Tomato Soup', 'Tomato, celeriac, carrot, sour cream', 5, 'images/tomato-soup.jpg', 5, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (20, 'Margherita', 'Pizza base, tomato sauce, cheese', 6, 'images/margherita.jpg', 6, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (21, 'Mexicana', 'Pizza base, tomato sauce, cheese, pepperoni', 6.5, 'images/mexicana.jpg', 6, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (22, 'Capricciosa', 'Pizza base, tomato sauce, cheese, pepperoni, olives', 6.5, 'images/capricciosa.jpg', 6, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (23, 'Tonno', 'Pizza base, tomato sauce, cheese, tuna, onion', 7, 'images/tonno.jpg', 6, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (24, 'Beef Goulash', 'Beef 100gr, tomato, carrot, celeriac', 6, 'images/daily-offer.jpg', 7, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (25, 'Natural Water', '', 2, 'images/natural-water.jpg', 8, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (26, 'Sparkling Water', '', 2, 'images/sparkling-water.jpg', 8, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (27, 'Ice Tea', '', 3, 'images/ice-tea.jpg', 8, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (28, 'Natural Juice', '', 3, 'images/natural-juice.jpg', 8, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (29, 'Coca Cola', '', 3, 'images/coca-cola.jpg', 8, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (30, 'Fanta', '', 3, 'images/fanta.jpg', 8, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (31, 'Tonic', '', 3, 'images/tonic.jpg', 8, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (32, 'Red Bull', '', 4, 'images/red-bull.jpg', 8, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (33, 'Small Espresso', '', 1.5, 'images/small-espresso.jpg', 9, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (34, 'Small Macchiato', '', 1.5, 'images/small-macchiato.jpg', 9, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (35, 'Large Espresso', '', 1.5, 'images/large-espresso.jpg', 9, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (36, 'Large Macchiato', '', 1.5, 'images/large-macchiato.jpg', 9, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (37, 'Vanilla Latte', '', 2, 'images/vanilla-latte.jpg', 9, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (38, 'Chocolate Latte', '', 2, 'images/chocolate-latte.jpg', 9, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (39, 'Tea', '', 2, 'images/tea.jpg', 9, 1);
INSERT INTO ITEM (itemid, name, description, price, imageurl, categoryid, available) VALUES (40, 'Hot Chocolate', '', 3, 'images/hot-chocolate.jpg', 9, 1);

INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (1,1,0);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (2,2,0);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (3,3,0);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (4,4,0);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (5,5,0);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (6,6,0);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (7,7,0);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (8,8,0);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (9,9,0);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (10,10,0);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (11,11,0);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (12,12,0);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (13,13,0);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (14,14,0);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (15,15,0);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (16,101,1);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (17,102,1);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (18,103,1);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (19,104,1);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (20,105,1);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (21,106,1);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (22,107,1);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (23,108,1);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (24,109,1);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (25,110,1);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (26,111,1);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (27,112,1);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (28,113,1);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (29,114,1);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (30,115,1);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (31,116,1);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (32,117,1);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (33,118,1);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (34,119,1);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (35,120,1);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (36,121,1);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (37,122,1);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (38,123,1);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (39,124,1);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (40,125,1);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (41,126,1);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (42,127,1);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (43,201,2);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (44,202,2);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (45,203,2);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (46,204,2);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (47,205,2);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (48,206,2);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (49,207,2);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (50,208,2);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (51,209,2);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (52,210,2);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (53,211,2);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (54,212,2);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (55,213,2);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (56,214,2);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (57,215,2);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (58,216,2);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (59,217,2);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (60,218,2);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (61,301,3);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (62,302,3);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (63,303,3);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (64,304,3);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (65,305,3);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (66,306,3);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (67,307,3);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (68,308,3);
INSERT INTO ROOM   (roomID, roomNumber, floor) VALUES (69,309,3);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (70,310,3);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (71,311,3);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (72,312,3);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (73,313,3);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (74,314,3);
INSERT INTO ROOM  (roomID, roomNumber, floor) VALUES (75,400,4);

INSERT INTO CUSTOMER (userID, name, surname, role, mail, password, dob) VALUES(1,'Lamija', 'Imamovic', 'student', 'lamija.imamovic@stu.ssst.edu.ba', 'lamsoni', STR_TO_DATE('2002-02-02', '%Y-%m-%d'));
INSERT INTO CUSTOMER (userID, name, surname, role, mail, password, dob) VALUES(2, 'Ena', 'Hamzic', 'student', 'ena.hamzic@stu.ssst.edu.ba','enoni', STR_TO_DATE('2003-06-12', '%Y-%m-%d'));
INSERT INTO CUSTOMER (userID, name, surname, role, mail, password, dob) VALUES(3,'Nejra', 'Berberovic', 'student', 'nejra.berberovic@stu.ssst.edu.ba', 'nejroni', STR_TO_DATE('2003-09-14', '%Y-%m-%d'));
INSERT INTO STAFF (staffID, name, surname, mail, password) VALUES(1,'Adi', 'Truba', 'truba.trubic@ssst.ba', 'truboni');
