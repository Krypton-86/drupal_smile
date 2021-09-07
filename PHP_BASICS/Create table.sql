CREATE TABLE smile.users (
                             user_id INT AUTO_INCREMENT,
                             First_name VARCHAR(255),
                             Last_name VARCHAR(255),
                             Email VARCHAR(255),
                             Birthday DATE,
                             Password VARCHAR(128),
                             Cars BOOLEAN,
                             Books BOOLEAN,
                             Travel BOOLEAN,
                             Music BOOLEAN,
                             Sport BOOLEAN,
                             IT BOOLEAN,
                             Movies BOOLEAN,
                             Games BOOLEAN,
                             Relax BOOLEAN,
                             News BOOLEAN,
                             PRIMARY KEY(user_id)
);