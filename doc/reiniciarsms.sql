SET FOREIGN_KEY_CHECKS = 0;

DELETE FROM Sms;
ALTER TABLE Sms AUTO_INCREMENT = 1;

DELETE FROM Mensaje;
ALTER TABLE Mensaje AUTO_INCREMENT = 1;

DELETE FROM ColumnaEvento;
ALTER TABLE ColumnaEvento AUTO_INCREMENT = 1;

DELETE FROM ColumnaResumen;
ALTER TABLE ColumnaResumen AUTO_INCREMENT = 1;

DELETE FROM Nombretsol;
ALTER TABLE Nombretsol AUTO_INCREMENT = 1;

DELETE FROM Nombrecorto;
ALTER TABLE Nombrecorto AUTO_INCREMENT = 1;

SET FOREIGN_KEY_CHECKS = 1;
