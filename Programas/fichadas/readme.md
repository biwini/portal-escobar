# Nuevos campos en la table Fichadas.dbo.licencias

Ejecute el siguiente script para agregar las columnas `Cerrado` y `CerradoMotivoId`

```
ALTER TABLE dbo.licencias
ADD 
	Cerrado BIT NULL DEFAULT 0,
	CerradoMotivoId BIGINT NULL;
````