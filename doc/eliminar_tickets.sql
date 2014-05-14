
CREATE PROCEDURE eliminar_tickets(in dia int)

BEGIN
	DELETE D FROM Incidencia I, Descripcion D WHERE I.id=D.incidencia_id AND I.fechaInsercion < DATE_SUB( NOW( ) , INTERVAL dia DAY );
	DELETE A FROM Incidencia I, Accion A WHERE I.id=A.incidencia_id AND I.fechaInsercion < DATE_SUB( NOW( ) , INTERVAL dia DAY );
	DELETE IA FROM Incidencia I, InfoAdjunto IA WHERE I.id=IA.incidencia_id AND I.fechaInsercion < DATE_SUB( NOW( ) , INTERVAL dia DAY );
	DELETE R FROM Incidencia I, Resolucion R WHERE I.id=R.incidencia_id AND I.fechaInsercion < DATE_SUB( NOW( ) , INTERVAL dia DAY );
	DELETE I FROM Incidencia I WHERE I.fechaInsercion < DATE_SUB( NOW( ) , INTERVAL dia DAY );
END$$