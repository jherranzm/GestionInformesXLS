delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Pestanyes_NoEnInforme`(idInforme int(11))
BEGIN

select 
    p.id as id, 
    p.nombre as nombre, 
    p.rango as rango, 
    p.numfilainicial as numfilainicial, 
    p.consulta_id as consultaid

FROM tbl_pestanyes p 
WHERE p.id not in (
    select pe.id 
    FROM tbl_pestanyes pe
    LEFT JOIN tbl_informe_pestanya ip on ip.pestanya_id = pe.id
    LEFT JOIN tbl_informesxls i on ip.informe_id = i.id
    WHERE i.id = idInforme
);

END$$

delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Pestanyes_ByInforme`(idInforme int(11))
BEGIN

SELECT
--     i.id as idInforme, 
    p.id as id, 
    ip.orden as orden,
    p.nombre as nombre, 
    p.rango as rango, 
    p.numfilainicial as numfilainicial, 
    p.consulta_id as consultaid
FROM tbl_pestanyes p
LEFT JOIN tbl_informe_pestanya ip on ip.pestanya_id = p.id
LEFT JOIN tbl_informesxls i on ip.informe_id = i.id
WHERE i.id = idInforme
ORDER BY ip.orden asc
;

END$$

delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Borrar_Pestanyes_EnInforme`(idInforme int(11))
BEGIN

DELETE FROM tbl_Informe_Pestanya WHERE informe_id = idInforme;

END$$

delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Add_Pestanya_EnInforme`(
idInforme int(11), 
idPestanya int(11),
o int(11)
)
BEGIN

INSERT INTO tbl_informe_pestanya (informe_id, pestanya_id, orden)
VALUES( idInforme, idPestanya, o);

END$$



