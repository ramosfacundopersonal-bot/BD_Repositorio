CREATE TABLE Usuario (
    ID_Us INT AUTO_INCREMENT,
    Nombre_y_Apellido VARCHAR(150) NOT NULL,
    Mail VARCHAR(100) NOT NULL,
    Telefono VARCHAR(20) NOT NULL,
    Contrasena VARCHAR(255) NOT NULL,
    PRIMARY KEY (ID_Us)
);

CREATE TABLE Moderador (
    ID_AGBD INT AUTO_INCREMENT,
    Nombre_y_Apellido VARCHAR(150) NOT NULL,
    Mail VARCHAR(100) NOT NULL,
    Telefono VARCHAR(20) NOT NULL,
    Contrasena VARCHAR(255) NOT NULL,
    PRIMARY KEY (ID_AGBD)
);

CREATE TABLE Chats (
    ID_Chat INT AUTO_INCREMENT,
    ID_Us INT NOT NULL,
    ID_AGBD INT NOT NULL,
    Mensajes TEXT NOT NULL,
    Miembros INT NOT NULL,
    PRIMARY KEY (ID_Chat),
    FOREIGN KEY (ID_Us) REFERENCES Usuario(ID_Us),
    FOREIGN KEY (ID_AGBD) REFERENCES Moderador(ID_AGBD)
);

```