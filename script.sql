create table CATEGORY
(
    categoryID  int auto_increment
        primary key,
    name        varchar(255) null,
    description text         null
);

create table CUSTOMER
(
    customerID int auto_increment
        primary key,
    name       varchar(255)         not null,
    surname    varchar(255)         not null,
    dob        datetime             not null,
    role       varchar(255)         not null,
    mail       varchar(255)         not null,
    password   varchar(255)         not null,
    isAdmin    tinyint(1) default 0 null,
    constraint mail
        unique (mail)
);

create index idx_customer_mail
    on CUSTOMER (mail);

create index idx_customer_name
    on CUSTOMER (name);

create index idx_customer_surname
    on CUSTOMER (surname);

create table ITEM
(
    itemID      int auto_increment
        primary key,
    name        varchar(255)         not null,
    price       float                not null,
    description text                 null,
    imageUrl    varchar(255)         null,
    categoryID  int                  null,
    available   tinyint(1) default 1 null,
    avgGrade    float      default 0 null,
    constraint ITEM_ibfk_1
        foreign key (categoryID) references CATEGORY (categoryID)
            on update cascade on delete set null,
    check (`avgGrade` between 0 and 5)
);

create index categoryID
    on ITEM (categoryID);

create index idx_item_name
    on ITEM (name);

create table ITEMREVIEW
(
    reviewID    int auto_increment
        primary key,
    customerID  int                  not null,
    itemID      int                  not null,
    description text                 null,
    grade       int                  null,
    reviewDate  date                 not null,
    disabled    tinyint(1) default 0 null,
    constraint ITEMREVIEW_ibfk_1
        foreign key (customerID) references CUSTOMER (customerID)
            on update cascade on delete cascade,
    constraint ITEMREVIEW_ibfk_2
        foreign key (itemID) references ITEM (itemID)
            on update cascade on delete cascade,
    check (`grade` between 1 and 5)
);

create index customerID
    on ITEMREVIEW (customerID);

create index idx_itemreview_date
    on ITEMREVIEW (reviewDate);

create index idx_itemreview_grade
    on ITEMREVIEW (grade);

create index itemID
    on ITEMREVIEW (itemID);

create definer = root@`%` trigger avgGradeCalculationDelete
    after delete
    on ITEMREVIEW
    for each row
BEGIN
    UPDATE ITEM i
    SET i.avgGrade = ROUND((
                               SELECT IFNULL(AVG(ir.grade), 0)
                               FROM ITEMREVIEW ir
                               WHERE ir.itemID = OLD.itemID
                           ), 2)
    WHERE i.itemID = OLD.itemID;
END;

create definer = root@`%` trigger avgGradeCalculationInsert
    after insert
    on ITEMREVIEW
    for each row
BEGIN
        UPDATE ITEM i
        SET i.avgGrade = ROUND((
            SELECT IFNULL(AVG(ir.grade), 0)
            FROM ITEMREVIEW ir
            WHERE ir.itemID = NEW.itemID
            ), 2)
        WHERE i.itemID = NEW.itemID;
    END;

create definer = root@`%` trigger avgGradeCalculationUpdate
    after update
    on ITEMREVIEW
    for each row
BEGIN
    UPDATE ITEM i
    SET i.avgGrade = ROUND((
                               SELECT IFNULL(AVG(ir.grade), 0)
                               FROM ITEMREVIEW ir
                               WHERE ir.itemID = NEW.itemID
                           ), 2)
    WHERE i.itemID = NEW.itemID;
END;

create table ROOM
(
    roomID     int auto_increment
        primary key,
    roomNumber int not null,
    floor      int not null
);

create index idx_roomnumber
    on ROOM (roomNumber);

create table STAFF
(
    staffID  int auto_increment
        primary key,
    name     varchar(255) not null,
    surname  varchar(255) not null,
    dob      datetime     not null,
    mail     varchar(255) not null,
    password varchar(255) not null,
    constraint mail
        unique (mail)
);

create table ORDERS
(
    orderID        int auto_increment
        primary key,
    status         int default 0  not null,
    orderDate      datetime       not null,
    grade          int            null,
    review         text           null,
    customer       int            null,
    sdeliverID     int            null,
    stakeID        int            null,
    price          decimal(10, 2) not null,
    paymentMethod  tinyint(1)     not null,
    deliveryOption tinyint(1)     not null,
    constraint ORDERS_ibfk_1
        foreign key (customer) references CUSTOMER (customerID)
            on update cascade on delete set null,
    constraint ORDERS_ibfk_2
        foreign key (sdeliverID) references STAFF (staffID)
            on update cascade on delete set null,
    constraint ORDERS_ibfk_3
        foreign key (stakeID) references STAFF (staffID)
            on update cascade on delete set null,
    check (`status` between 0 and 6)
);

create table DELIVERY_ROOM
(
    deliveryroomID int auto_increment
        primary key,
    roomID         int      not null,
    orderID        int      not null,
    scheduledtime  datetime null,
    actualtime     datetime null,
    constraint DELIVERY_ROOM_ibfk_1
        foreign key (roomID) references ROOM (roomID)
            on update cascade on delete cascade,
    constraint DELIVERY_ROOM_ibfk_2
        foreign key (orderID) references ORDERS (orderID)
            on update cascade on delete cascade
);

create index orderID
    on DELIVERY_ROOM (orderID);

create index roomID
    on DELIVERY_ROOM (roomID);

create index idx_order_customer
    on ORDERS (customer);

create index idx_order_date
    on ORDERS (orderDate);

create index idx_order_deliverer
    on ORDERS (sdeliverID);

create index idx_order_taker
    on ORDERS (stakeID);

create table ORDER_ITEM
(
    orderitemID int auto_increment
        primary key,
    orderID     int            not null,
    itemID      int            null,
    quantity    int            not null,
    price       decimal(10, 2) not null,
    constraint ORDER_ITEM_ibfk_1
        foreign key (orderID) references ORDERS (orderID)
            on update cascade on delete cascade,
    constraint ORDER_ITEM_ibfk_2
        foreign key (itemID) references ITEM (itemID)
            on update cascade on delete set null
);

create index idx_orderitem_item
    on ORDER_ITEM (itemID);

create index idx_orderitem_order
    on ORDER_ITEM (orderID);

create index idx_staff_mail
    on STAFF (mail);

create index idx_staff_name
    on STAFF (name);

create index idx_staff_surname
    on STAFF (surname);

create definer = root@`%` view StaffOrdersDetailed as
select `o`.`orderID`                                                   AS `orderId`,
       `o`.`orderDate`                                                 AS `orderDate`,
       `o`.`status`                                                    AS `status`,
       `o`.`grade`                                                     AS `grade`,
       `c`.`customerID`                                                AS `customerId`,
       `c`.`name`                                                      AS `customerName`,
       `c`.`surname`                                                   AS `customerSurname`,
       `st_take`.`staffID`                                             AS `takeStaffId`,
       `st_take`.`name`                                                AS `takeStaffName`,
       `st_take`.`surname`                                             AS `takeStaffSurname`,
       `st_deliver`.`staffID`                                          AS `deliverStaffId`,
       `st_deliver`.`name`                                             AS `deliverStaffName`,
       `st_deliver`.`surname`                                          AS `deliverStaffSurname`,
       group_concat(`i`.`name` order by `i`.`name` ASC separator ', ') AS `items`,
       sum((`oi`.`price` * `oi`.`quantity`))                           AS `total_price`
from (((((`project`.`ORDERS` `o` join `project`.`CUSTOMER` `c`
          on ((`o`.`customer` = `c`.`customerID`))) left join `project`.`STAFF` `st_take`
         on ((`o`.`stakeID` = `st_take`.`staffID`))) left join `project`.`STAFF` `st_deliver`
        on ((`o`.`sdeliverID` = `st_deliver`.`staffID`))) join `project`.`ORDER_ITEM` `oi`
       on ((`o`.`orderID` = `oi`.`orderID`))) join `project`.`ITEM` `i` on ((`oi`.`itemID` = `i`.`itemID`)))
group by `o`.`orderID`;

create
    definer = root@`%` procedure DisableOldReviews(IN p_itemId int, IN p_days int)
BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE currentItemId INT;

    -- Declare cursor for iterating through all item IDs
    DECLARE itemCursor CURSOR FOR
        SELECT itemId FROM ITEM;

    -- Declare handler for cursor end
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    -- If p_itemId is not null, disable reviews for that specific item
    IF p_itemId IS NOT NULL THEN
        UPDATE `ITEMREVIEW`
        SET disabled = TRUE
        WHERE itemId = p_itemId AND reviewDate < DATE_SUB(NOW(), INTERVAL p_days DAY);
    ELSE
        -- If p_itemId is null, disable reviews for all items
        OPEN itemCursor;

        read_loop: LOOP
            FETCH itemCursor INTO currentItemId;
            IF done THEN
                LEAVE read_loop;
            END IF;

            UPDATE `ITEMREVIEW`
            SET disabled = TRUE
            WHERE itemId = currentItemId AND reviewDate < DATE_SUB(NOW(), INTERVAL p_days DAY);
        END LOOP;

        CLOSE itemCursor;
    END IF;
END;

create definer = root@`%` event DisableOldReviewsDaily on schedule
    every '1' DAY
        starts '2024-06-04 19:38:11'
    enable
    do
    BEGIN
        CALL DisableOldReviews(NULL, 90);
    END;


