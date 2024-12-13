USE library_management_system;
ALTER TABLE publisher
ADD avatar_url VARCHAR(255)


CREATE TABLE loan_detail (
    detail_id INT PRIMARY KEY AUTO_INCREMENT,
    loan_id INT NOT NULL,
    book_id INT NOT NULL,
    quantity INT DEFAULT 1,
    status ENUM('issued', 'returned', 'lost', 'damaged') DEFAULT 'issued',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (loan_id) REFERENCES loan(loan_id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES book(book_id) ON DELETE CASCADE
);

INSERT INTO loan_detail (loan_id, book_id, quantity, status, notes, created_at)
SELECT loan_id, book_id, 1, status, notes, created_at
FROM loan;

--ALTER TABLE loan DROP FOREIGN KEY loan_ibfk_1;
-- Alter table loan drop column book_id
--ALTER TABLE loan
--ADD CONSTRAINT fk_user1
--FOREIGN KEY (user_id)
--REFERENCES user(user_id)
--ON DELETE CASCADE;

--ALTER TABLE loan ADD user_id int;
--ALTER TABLE author ADD avatar_url VARCHAR(255);



