DELIMITER ;

DROP TABLE IF EXISTS PalabrasXUsuario;
DROP TABLE IF EXISTS PalabrasXNoticiaActiva;
DROP TABLE IF EXISTS Palabras;
DROP TABLE IF EXISTS CaracteresXPalabraSemejante;
DROP TABLE IF EXISTS PalabrasSemejantes;
DROP TABLE IF EXISTS Usuarios;
DROP TABLE IF EXISTS EstatusComentariosNoticias;
DROP TABLE IF EXISTS HistoricoEstatusComentariosNoticias;
DROP TABLE IF EXISTS RespuestasComentariosNoticias;
DROP TABLE IF EXISTS ComentariosNoticias;
DROP TABLE IF EXISTS NoticiasActivas;
DROP TABLE IF EXISTS HistoricoNoticias;

CREATE TABLE IF NOT EXISTS PalabrasSemejantes (
    IdPalabraSemejante INT NOT NULL,
    PalabraSemejante VARCHAR(100) NOT NULL,
    EstaLibre BIT NOT NULL,
    PRIMARY KEY (IdPalabraSemejante),
    CONSTRAINT IX_PalabraSemejante UNIQUE (PalabraSemejante)
);

CREATE TABLE IF NOT EXISTS CaracteresXPalabraSemejante (
    IdPalabraSemejante INT NOT NULL,
    Caracter VARCHAR(1) NOT NULL,
    PRIMARY KEY (IdPalabraSemejante, Caracter),
    FOREIGN KEY (IdPalabraSemejante) REFERENCES PalabrasSemejantes(IdPalabraSemejante)
);

CREATE TABLE IF NOT EXISTS Palabras (
    IdPalabra INT NOT NULL,
    Palabra VARCHAR(100) NOT NULL,
    EstaLibre BIT NOT NULL,
    IdPalabraSemejante INT NOT NULL,
    PRIMARY KEY (IdPalabra),
    CONSTRAINT IX_Palabra UNIQUE (Palabra),
    FOREIGN KEY (IdPalabraSemejante) REFERENCES PalabrasSemejantes(IdPalabraSemejante)
);

CREATE TABLE IF NOT EXISTS Usuarios (
    IdUsuario INT NOT NULL,
    Usuario VARCHAR(50) NOT NULL,
    Cedula VARCHAR(100) NOT NULL,
    Nombre VARCHAR(100) NOT NULL,
    Contrasena VARCHAR(50) NOT NULL,    
    EsAdministrador BIT NOT NULL,
    PRIMARY KEY (IdUsuario),
    CONSTRAINT IX_Usuario UNIQUE (Usuario)
);

CREATE TABLE IF NOT EXISTS PalabrasXUsuario (
    IdUsuario INT NOT NULL,
    IdPalabra INT NOT NULL,
    PRIMARY KEY (IdUsuario, IdPalabra),
    FOREIGN KEY (IdUsuario) REFERENCES Usuarios(IdUsuario),
    FOREIGN KEY (IdPalabra) REFERENCES Palabras(IdPalabra)
);

CREATE TABLE IF NOT EXISTS HistoricoNoticias (
    IdNoticia INT NOT NULL,
    Consecutivo INT NOT NULL,
    FechaPublicacionNoticia DATE NOT NULL,
    Titulo VARCHAR(100) NOT NULL,
    Resumen VARCHAR(200) NOT NULL,
    URLRelativaNoticia VARCHAR(100) NOT NULL,
    IdUsuarioAutor INT NOT NULL,
    IdUsuarioModificoRegistro INT NOT NULL,
    FechaHoraModificacionRegistro DATETIME NOT NULL,    
    PRIMARY KEY (IdNoticia, Consecutivo),
    FOREIGN KEY (IdUsuarioAutor) REFERENCES Usuarios(IdUsuario),
    FOREIGN KEY (IdUsuarioModificoRegistro) REFERENCES Usuarios(IdUsuario)
);

CREATE TABLE IF NOT EXISTS NoticiasActivas (
    IdNoticia INT NOT NULL,
    Consecutivo INT NOT NULL,
    PRIMARY KEY (IdNoticia),
    FOREIGN KEY (IdNoticia, Consecutivo) REFERENCES HistoricoNoticias(IdNoticia, Consecutivo)
);

CREATE TABLE IF NOT EXISTS PalabrasXNoticiaActiva (
    IdNoticia INT NOT NULL,
    IdPalabra INT NOT NULL,
    PRIMARY KEY (IdNoticia, IdPalabra),
    FOREIGN KEY (IdNoticia) REFERENCES NoticiasActivas(IdNoticia),
    FOREIGN KEY (IdPalabra) REFERENCES Palabras(IdPalabra)
);

CREATE TABLE IF NOT EXISTS ComentariosNoticias (
    IdNoticia INT NOT NULL,
    Consecutivo INT NOT NULL,    
    FechaHoraEnvio DATETIME NOT NULL,    
    NombreComentarista VARCHAR(100) NOT NULL,
    EmailComentarista VARCHAR(50) NOT NULL,
    Comentario VARCHAR(300) NOT NULL,
    PRIMARY KEY (IdNoticia, Consecutivo),
    FOREIGN KEY (IdNoticia) REFERENCES NoticiasActivas(IdNoticia)
);

CREATE TABLE IF NOT EXISTS RespuestasComentariosNoticias (
    IdNoticia INT NOT NULL,
    ConsecutivoResponde INT NOT NULL,    
    ConsecutivoRespuesta INT NOT NULL,
    PRIMARY KEY (IdNoticia, ConsecutivoRespuesta),
    FOREIGN KEY (IdNoticia, ConsecutivoRespuesta) REFERENCES ComentariosNoticias(IdNoticia, Consecutivo),
    FOREIGN KEY (IdNoticia, ConsecutivoResponde) REFERENCES ComentariosNoticias(IdNoticia, Consecutivo)
);

CREATE TABLE IF NOT EXISTS HistoricoEstatusComentariosNoticias (
    IdNoticia INT NOT NULL,
    ConsecutivoComentario INT NOT NULL,
    ConsecutivoEstatus INT NOT NULL,    
    Estatus BIT(2) NOT NULL,
    FechaHoraModificacionEstatus DATETIME NOT NULL,        
    IdUsuarioModificoEstatus INT NOT NULL,        
    PRIMARY KEY (IdNoticia, ConsecutivoComentario, ConsecutivoEstatus),
    FOREIGN KEY (IdNoticia, ConsecutivoComentario) REFERENCES ComentariosNoticias(IdNoticia, Consecutivo),
    FOREIGN KEY (IdUsuarioModificoEstatus) REFERENCES Usuarios(IdUsuario)
);

CREATE TABLE IF NOT EXISTS EstatusComentariosNoticias (
    IdNoticia INT NOT NULL,
    ConsecutivoComentario INT NOT NULL,
    ConsecutivoEstatus INT NOT NULL,        
    PRIMARY KEY (IdNoticia, ConsecutivoComentario),    
    FOREIGN KEY (IdNoticia, ConsecutivoComentario, ConsecutivoEstatus) REFERENCES HistoricoEstatusComentariosNoticias(IdNoticia, ConsecutivoComentario, ConsecutivoEstatus)
);

DROP PROCEDURE IF EXISTS DemeSiguientePalabra;
DROP PROCEDURE IF EXISTS DemePalabraSemejante;
DROP PROCEDURE IF EXISTS ValidarContrasena;
DROP PROCEDURE IF EXISTS Encriptar;
DROP PROCEDURE IF EXISTS SonHilerasIdenticasBitXBit;
DROP PROCEDURE IF EXISTS CambiarContrasena;
DROP PROCEDURE IF EXISTS ValidarLogin;
DROP PROCEDURE IF EXISTS IndexarPalabraSemejante;
DROP PROCEDURE IF EXISTS LiberarPalabrasSemejantesNoUtilizadas;
DROP PROCEDURE IF EXISTS IndexarPalabra;
DROP PROCEDURE IF EXISTS LiberarPalabrasNoUtilizadas;

DROP PROCEDURE IF EXISTS IndexarUsuario;
DROP PROCEDURE IF EXISTS IndexarTodosUsuarios;
DROP PROCEDURE IF EXISTS ValidarCamposUsuario;
DROP PROCEDURE IF EXISTS AltaUsuario;
DROP PROCEDURE IF EXISTS CambioUsuario;

DROP PROCEDURE IF EXISTS IndexarNoticia;
DROP PROCEDURE IF EXISTS IndexarTodasNoticias;

DROP PROCEDURE IF EXISTS ValidarCamposComentarioNoticia;
DROP PROCEDURE IF EXISTS AltaComentarioNoticia;

DROP PROCEDURE IF EXISTS CambioEstatusComentarioNoticia;

DELIMITER $$

CREATE PROCEDURE DemeSiguientePalabra(IN HileraXAnalizar VARCHAR(500), IN CaracteresValidos VARCHAR(500), IN IndiceInicial INT, OUT SiguientePalabra VARCHAR(500), OUT NuevoIndice INT, IN TamanoMaximoPalabra INT, IN RetornarResultadosEnSelect BIT(1))
BEGIN
  SET NuevoIndice = IndiceInicial;
  SET SiguientePalabra = '';

  WHILE NuevoIndice <= CHAR_LENGTH(HileraXAnalizar) AND POSITION(SUBSTRING(HileraXAnalizar, NuevoIndice, 1) IN CaracteresValidos) < 1 DO
    SET NuevoIndice = NuevoIndice + 1;
  END WHILE;

  WHILE NuevoIndice <= CHAR_LENGTH(HileraXAnalizar) AND POSITION(SUBSTRING(HileraXAnalizar, NuevoIndice, 1) IN CaracteresValidos) >= 1 DO
    SET SiguientePalabra = CONCAT(SiguientePalabra, SUBSTRING(HileraXAnalizar, NuevoIndice, 1));
    SET NuevoIndice = NuevoIndice + 1;
  END WHILE;

  IF TamanoMaximoPalabra >= 0 THEN
    IF TamanoMaximoPalabra = 0 THEN
      SET SiguientePalabra = '';
    ELSE
      SET SiguientePalabra = MID(SiguientePalabra, 1, TamanoMaximoPalabra);
    END IF;
  END IF;
  
  IF RetornarResultadosEnSelect = 1 THEN
    SELECT SiguientePalabra, NuevoIndice;
  END IF;
END;

DELIMITER $$

CREATE PROCEDURE DemePalabraSemejante(IN Palabra VARCHAR(500), IN TuplasReemplazos VARCHAR(500), IN SeparadorTuplas VARCHAR(1), IN SeparadorColumnas VARCHAR(1), OUT PalabraSemejante VARCHAR(500), IN RetornarResultadosEnSelect BIT(1))
BEGIN
/* Advertencia: La hilera 'TuplasReemplazos' debe contener espacios en blanco donde se requiera, pues este procedimiento no reemplaza ninguno */

  SET PalabraSemejante = LOWER(Palabra);
  SET TuplasReemplazos = LOWER(TuplasReemplazos);
  /* Los anteriores dos reemplazos son para que no se distinga entre caracteres en minúscula y mayúscula */

  SET @Tupla = SUBSTRING_INDEX(TuplasReemplazos, SeparadorTuplas, 1);
  
  WHILE CHAR_LENGTH(@Tupla) >= 1 DO
    SET @AReemplazar = SUBSTRING_INDEX(@Tupla, SeparadorColumnas, 1);
    
    IF CHAR_LENGTH(@AReemplazar) >= 1 AND INSTR(PalabraSemejante, @AReemplazar) >= 1 THEN
      SET @Reemplazo = SUBSTRING(@Tupla, CHAR_LENGTH(@AReemplazar) + CHAR_LENGTH(SeparadorColumnas) + 1);
      SET PalabraSemejante = REPLACE(PalabraSemejante, @AReemplazar, @Reemplazo);
    END IF;

    SET TuplasReemplazos = SUBSTRING(TuplasReemplazos, CHAR_LENGTH(@Tupla) + CHAR_LENGTH(SeparadorTuplas) + 1);
    SET @Tupla = SUBSTRING_INDEX(TuplasReemplazos, SeparadorTuplas, 1);
  END WHILE;
      
  IF RetornarResultadosEnSelect = 1 THEN
    SELECT PalabraSemejante;
  END IF;
END;

DELIMITER $$

CREATE PROCEDURE ValidarContrasena(IN Contrasena VARCHAR(500), OUT NumError INT, IN LongitudMinimaContrasena INT, IN CaracteresEspeciales VARCHAR(500), IN RetornarResultadosEnSelect BIT(1))
BEGIN
  SET NumError = 0;
  
  SET @CaracteresAlfabeticos = "abcdefghijklmnñopqrstuvwxyzáéíóúü";
  SET @CaracteresAlfabeticosMayuscula = UPPER(@CaracteresAlfabeticos);
  SET @CaracteresAlfabeticosMinuscula = LOWER(@CaracteresAlfabeticos);
  SET @DigitosDecimales = "0123456789";
  
  SET @ContadorCaracteresAlfabeticosMayuscula = 0;
  SET @ContadorCaracteresAlfabeticosMinuscula = 0;
  SET @ContadorDigitosDecimales = 0;
  SET @ContadorCaracteresEspeciales = 0;
  
  IF (CHAR_LENGTH(Contrasena) < LongitudMinimaContrasena) THEN
    SET NumError = 1;
  ELSE
    SET @Indice = 1;

    WHILE @Indice <= CHAR_LENGTH(Contrasena) DO
      SET @SiguienteCaracter = MID(Contrasena, @Indice, 1);
    
      IF SUBSTRING_INDEX(@CaracteresAlfabeticosMayuscula, @SiguienteCaracter, 1) != @CaracteresAlfabeticosMayuscula THEN
        SET @ContadorCaracteresAlfabeticosMayuscula = @ContadorCaracteresAlfabeticosMayuscula + 1;
      
      ELSEIF SUBSTRING_INDEX(@CaracteresAlfabeticosMinuscula, @SiguienteCaracter, 1) != @CaracteresAlfabeticosMinuscula THEN
        SET @ContadorCaracteresAlfabeticosMinuscula = @ContadorCaracteresAlfabeticosMinuscula + 1;

      ELSEIF SUBSTRING_INDEX(@DigitosDecimales, @SiguienteCaracter, 1) != @DigitosDecimales THEN
        SET @ContadorDigitosDecimales = @ContadorDigitosDecimales + 1;

      ELSEIF SUBSTRING_INDEX(CaracteresEspeciales, @SiguienteCaracter, 1) != CaracteresEspeciales THEN
        SET @ContadorCaracteresEspeciales = @ContadorCaracteresEspeciales + 1;
      
      END IF;

      SET @Indice = @Indice + 1;
    END WHILE;
    
    IF @ContadorCaracteresAlfabeticosMayuscula < 1 OR @ContadorCaracteresAlfabeticosMinuscula < 1 OR @ContadorDigitosDecimales < 1 OR @ContadorCaracteresEspeciales < 1 THEN
      SET NumError = 2;
    END IF;
  END IF;
  
  IF RetornarResultadosEnSelect = 1 THEN
      SELECT NumError;
  END IF;
END;

DELIMITER $$

CREATE PROCEDURE Encriptar(IN HileraXEncriptar VARCHAR(500), IN CodigoEncriptacion VARCHAR(500), OUT HileraEncriptada VARCHAR(500), IN RetornarResultadosEnSelect BIT(1))
BEGIN
  SET HileraEncriptada = '';
  
  IF CHAR_LENGTH(CodigoEncriptacion) > 0 THEN
    SET @MaximoNumeroCaracteresXCodificar = POWER(2, 7);
    SET @IndiceActual = 0;
    
    WHILE @IndiceActual < CHAR_LENGTH(HileraXEncriptar) DO
      SET @PosicionEnCodigoEncriptacion = MOD(@IndiceActual, CHAR_LENGTH(CodigoEncriptacion)) + 1;
      SET @SiguienteCaracterEnCodigoEncriptacion = MID(CodigoEncriptacion, @PosicionEnCodigoEncriptacion, 1);
      SET @SiguienteCaracterEnHileraXEncriptar = MID(HileraXEncriptar, @IndiceActual + 1, 1);
      SET @SiguienteCodigoEnHileraEncriptada = ORD(@SiguienteCaracterEnCodigoEncriptacion) + ORD(@SiguienteCaracterEnHileraXEncriptar);
      SET @SiguienteCodigoEnHileraEncriptada = MOD(@SiguienteCodigoEnHileraEncriptada, @MaximoNumeroCaracteresXCodificar);
      SET @SiguienteCaracterEnHileraEncriptada = CHAR(@SiguienteCodigoEnHileraEncriptada);
      SET HileraEncriptada = CONCAT(HileraEncriptada, @SiguienteCaracterEnHileraEncriptada);
      SET @IndiceActual = @IndiceActual + 1;
    END WHILE;
  END IF;
  
  IF RetornarResultadosEnSelect = 1 THEN
    SELECT HileraEncriptada;
  END IF;
END;

DELIMITER $$

CREATE PROCEDURE SonHilerasIdenticasBitXBit(IN HileraComparar1 VARCHAR(500), IN HileraComparar2 VARCHAR(500), OUT SonIguales BIT(1), IN RetornarResultadosEnSelect BIT(1))
BEGIN
  SET SonIguales = 1;
  
  IF CHAR_LENGTH(HileraComparar1) != CHAR_LENGTH(HileraComparar2) THEN
    SET SonIguales = 0;
  ELSE
    SET @IndiceActual = 1;
    
    WHILE SonIguales = 1 AND @IndiceActual <= CHAR_LENGTH(HileraComparar1) DO
      SET @SiguienteCaracterEnHilera1 = MID(HileraComparar1, @IndiceActual, 1);
      SET @SiguienteCaracterEnHilera2 = MID(HileraComparar2, @IndiceActual, 1);
      
      IF ORD(@SiguienteCaracterEnHilera1) != ORD(@SiguienteCaracterEnHilera2) THEN
          SET SonIguales = 0;
      END IF;
      
      SET @IndiceActual = @IndiceActual + 1;
    END WHILE;
  END IF;
  
  IF RetornarResultadosEnSelect = 1 THEN
    SELECT SonIguales;
  END IF;
END;

DELIMITER $$

CREATE PROCEDURE CambiarContrasena(IN UnIdUsuario INT, IN ContrasenaAnterior VARCHAR(500), IN NuevaContrasena VARCHAR(500), IN ConfirmacionNuevaContrasena VARCHAR(500), OUT NumError INT, IN CodigoEncriptacion VARCHAR(500), IN LongitudMinimaContrasena INT, IN CaracteresEspeciales VARCHAR(500), IN RetornarResultadosEnSelect BIT(1))
BEGIN
  START TRANSACTION;

  SET NumError = 0;
  SET @IncrementosNumBaseError = 1000;
  SET @NumBaseError = @IncrementosNumBaseError;
  
  CALL Encriptar(ContrasenaAnterior, CodigoEncriptacion, @ContrasenaAnteriorEncriptada, 0);
  
  SELECT COUNT(1) FROM Usuarios WHERE IdUsuario = UnIdUsuario AND Contrasena = @ContrasenaAnteriorEncriptada INTO @CantidadUsuarios;
  
  IF @CantidadUsuarios != 1 THEN
    SET NumError = @NumBaseError + 1;
  ELSE
    SET @NumBaseError = @NumBaseError + @IncrementosNumBaseError;
    
    CALL SonHilerasIdenticasBitXBit(NuevaContrasena, ConfirmacionNuevaContrasena, @SonIguales, 0);
    
    IF @SonIguales = 0 THEN
      SET NumError = @NumBaseError + 1;
    ELSE
      SET @NumBaseError = @NumBaseError + @IncrementosNumBaseError;
      
      CALL ValidarContrasena(NuevaContrasena, NumError, LongitudMinimaContrasena, CaracteresEspeciales, 0);
      
      IF NumError != 0 THEN
        SET NumError = @NumBaseError + NumError;
      END IF;
    END IF;
  END IF;
  
  IF NumError = 0 THEN
    CALL Encriptar(NuevaContrasena, CodigoEncriptacion, @NuevaContrasenaEncriptada, 0);
    UPDATE Usuarios SET Contrasena = @NuevaContrasenaEncriptada WHERE IdUsuario = UnIdUsuario;
  END IF;

  IF RetornarResultadosEnSelect = 1 THEN
    SELECT NumError;
  END IF;

  COMMIT;
END;

DELIMITER $$

CREATE PROCEDURE ValidarLogin(IN UnUsuario VARCHAR(500), IN UnaContrasena VARCHAR(500), IN CodigoEncriptacion VARCHAR(500), OUT UsuarioContrasenaExiste BIT(1), OUT UnIdUsuario INT, IN RetornarResultadosEnSelect BIT(1))
BEGIN
  SET UsuarioContrasenaExiste = 0;
  CALL Encriptar(UnaContrasena, CodigoEncriptacion, @UnaContrasenaEncriptada, 0);
  
  SELECT COUNT(1), MIN(IdUsuario) FROM Usuarios WHERE Usuario = UnUsuario AND Contrasena = @UnaContrasenaEncriptada INTO @CantidadUsuarios, UnIdUsuario;
  
  IF @CantidadUsuarios = 1 THEN
    SET UsuarioContrasenaExiste = 1;
  END IF;

  IF RetornarResultadosEnSelect = 1 THEN
    SELECT UsuarioContrasenaExiste, UnIdUsuario;
  END IF;
END;

DELIMITER $$

CREATE PROCEDURE IndexarPalabraSemejante(IN UnaPalabraSemejante VARCHAR(500), IN RetornarResultadosEnSelect BIT(1))
BEGIN
  START TRANSACTION;
  
  SET @ExistePalabraSemejante = 1;
  SELECT MIN(IdPalabraSemejante) FROM PalabrasSemejantes WHERE PalabraSemejante = UnaPalabraSemejante INTO @IdPalabraSemejante;
  
  IF ISNULL(@IdPalabraSemejante) = 1 THEN
    SELECT MIN(IdPalabraSemejante) FROM PalabrasSemejantes WHERE EstaLibre = 1 INTO @IdPalabraSemejante;
  END IF;
  
  IF ISNULL(@IdPalabraSemejante) = 1 THEN
    SET @ExistePalabraSemejante = 0;
    SELECT MAX(IdPalabraSemejante) FROM PalabrasSemejantes INTO @IdPalabraSemejante;
      
    IF ISNULL(@IdPalabraSemejante) = 1 THEN
      SET @IdPalabraSemejante = 0;
    END IF;
    
    SET @IdPalabraSemejante = @IdPalabraSemejante + 1;
  END IF;
  
  IF @ExistePalabraSemejante = 0 THEN
    INSERT INTO PalabrasSemejantes(IdPalabraSemejante, PalabraSemejante, EstaLibre) VALUES(@IdPalabraSemejante, UnaPalabraSemejante, 0);
  ELSE
    UPDATE PalabrasSemejantes SET PalabraSemejante = UnaPalabraSemejante, EstaLibre = 0 WHERE IdPalabraSemejante = @IdPalabraSemejante;
  END IF;
    
  DELETE FROM CaracteresXPalabraSemejante WHERE IdPalabraSemejante = @IdPalabraSemejante;

  IF CHAR_LENGTH(UnaPalabraSemejante) = 0 THEN
    INSERT INTO CaracteresXPalabraSemejante(IdPalabraSemejante, Caracter) VALUES(@IdPalabraSemejante, '');
  ELSE
    SET @IndiceActual = 1;
    SET @CaracteresInsertados = "";
      
    WHILE @IndiceActual <= CHAR_LENGTH(UnaPalabraSemejante) DO
      SET @SiguienteCaracter = MID(UnaPalabraSemejante, @IndiceActual, 1);
        
      IF INSTR(@CaracteresInsertados, @SiguienteCaracter) < 1 THEN
        INSERT INTO CaracteresXPalabraSemejante(IdPalabraSemejante, Caracter) VALUES(@IdPalabraSemejante, @SiguienteCaracter);
        SET @CaracteresInsertados = CONCAT(@CaracteresInsertados, @SiguienteCaracter);
      END IF;
        
      SET @IndiceActual = @IndiceActual + 1;
    END WHILE;
  END IF;
      
  IF RetornarResultadosEnSelect = 1 THEN
    SELECT a.IdPalabraSemejante, a.PalabraSemejante, a.EstaLibre, b.Caracter
    FROM PalabrasSemejantes a, CaracteresXPalabraSemejante b
    WHERE a.IdPalabraSemejante = b.IdPalabraSemejante
    AND a.PalabraSemejante = UnaPalabraSemejante;
  END IF;
  
  COMMIT;
END;

DELIMITER $$

CREATE PROCEDURE LiberarPalabrasSemejantesNoUtilizadas()
BEGIN
  START TRANSACTION;
  
  UPDATE PalabrasSemejantes
  SET EstaLibre = 1
  WHERE NOT IdPalabraSemejante IN (SELECT IdPalabraSemejante FROM Palabras WHERE EstaLibre = 0);
  
  COMMIT;
END;

DELIMITER $$

CREATE PROCEDURE IndexarPalabra(IN UnaPalabra VARCHAR(500), IN TuplasReemplazos VARCHAR(500), IN SeparadorTuplas VARCHAR(1), IN SeparadorColumnas VARCHAR(1), IN RetornarResultadosEnSelect BIT(1))
BEGIN
  START TRANSACTION;
  
  SET @ExistePalabra = 1;
  SELECT MIN(IdPalabra) FROM Palabras WHERE Palabra = UnaPalabra INTO @IdPalabra;
  
  IF ISNULL(@IdPalabra) = 1 THEN
    SELECT MIN(IdPalabra) FROM Palabras WHERE EstaLibre = 1 INTO @IdPalabra;
  END IF;
  
  IF ISNULL(@IdPalabra) = 1 THEN
    SET @ExistePalabra = 0;
    SELECT MAX(IdPalabra) FROM Palabras INTO @IdPalabra;
      
    IF ISNULL(@IdPalabra) = 1 THEN
      SET @IdPalabra = 0;
    END IF;
    
    SET @IdPalabra = @IdPalabra + 1;
  END IF;

  CALL DemePalabraSemejante(UnaPalabra, TuplasReemplazos, SeparadorTuplas, SeparadorColumnas, @PalabraSemejante, 0);
  CALL IndexarPalabraSemejante(@PalabraSemejante, 0);

  SELECT MIN(IdPalabraSemejante) FROM PalabrasSemejantes WHERE PalabraSemejante = @PalabraSemejante INTO @IdPalabraSemejante;

  IF @ExistePalabra = 0 THEN
    INSERT INTO Palabras(IdPalabra, Palabra, EstaLibre, IdPalabraSemejante) VALUES(@IdPalabra, UnaPalabra, 0, @IdPalabraSemejante);
  ELSE
    UPDATE Palabras SET Palabra = UnaPalabra, EstaLibre = 0, IdPalabraSemejante = @IdPalabraSemejante WHERE IdPalabra = @IdPalabra;
  END IF;
  
  CALL LiberarPalabrasSemejantesNoUtilizadas();

  IF RetornarResultadosEnSelect = 1 THEN
    SELECT a.IdPalabra, a.Palabra, a.EstaLibre, b.IdPalabraSemejante, b.PalabraSemejante, b.EstaLibre, c.Caracter
    FROM Palabras a, PalabrasSemejantes b, CaracteresXPalabraSemejante c
    WHERE a.IdPalabraSemejante = b.IdPalabraSemejante
    AND b.IdPalabraSemejante = c.IdPalabraSemejante
    AND a.Palabra = UnaPalabra;
  END IF;
  
  COMMIT;
END;

DELIMITER $$

CREATE PROCEDURE LiberarPalabrasNoUtilizadas()
BEGIN
  START TRANSACTION;
  
  UPDATE Palabras
  SET EstaLibre = 1
  WHERE NOT IdPalabra IN (SELECT IdPalabra FROM PalabrasXUsuario)
  AND NOT IdPalabra IN (SELECT IdPalabra FROM PalabrasXNoticiaActiva);  
          
  COMMIT;
END;

DELIMITER $$
 
CREATE PROCEDURE IndexarUsuario(IN UnIdUsuario INT, IN CaracteresValidos VARCHAR(500), IN TuplasReemplazos VARCHAR(500), IN SeparadorTuplas VARCHAR(1), IN SeparadorColumnas VARCHAR(1), IN SeparadorPalabras VARCHAR(1), IN TamanoMaximoPalabras INT, IN RetornarResultadosEnSelect BIT(1))
BEGIN
  START TRANSACTION;
  
  DELETE FROM PalabrasXUsuario WHERE IdUsuario = UnIdUsuario;
  CALL LiberarPalabrasNoUtilizadas();
  
  SELECT COUNT(1) FROM Usuarios WHERE IdUsuario = UnIdUsuario INTO @CantidadUsuarios;
  
  IF (@CantidadUsuarios = 1) THEN
    SELECT LOWER(CONCAT(Usuario, SeparadorPalabras, Cedula, SeparadorPalabras, Nombre)) FROM Usuarios WHERE IdUsuario = UnIdUsuario GROUP BY Usuario, Cedula, Nombre INTO @PalabrasXIndexar;
  
    SET @PalabrasIndexadas = '';

    CALL DemeSiguientePalabra(@PalabrasXIndexar, CaracteresValidos, 1, @SiguientePalabra, @NuevoIndice, TamanoMaximoPalabras, 0);
    
    WHILE CHAR_LENGTH(@SiguientePalabra) > 0 DO
      SET @SiguientePalabraXIndexarConSeparadoresPalabras = CONCAT(SeparadorPalabras, @SiguientePalabra, SeparadorPalabras);
      
      IF POSITION(@SiguientePalabraXIndexarConSeparadoresPalabras IN @PalabrasIndexadas) < 1 THEN
        CALL IndexarPalabra(@SiguientePalabra, TuplasReemplazos, SeparadorTuplas, SeparadorColumnas, 0);
        SELECT MIN(IdPalabra) FROM Palabras WHERE Palabra = @SiguientePalabra INTO @IdPalabra;
        INSERT INTO PalabrasXUsuario(IdUsuario, IdPalabra) VALUES(UnIdUsuario, @IdPalabra);
        SET @PalabrasIndexadas = CONCAT(@PalabrasIndexadas, @SiguientePalabraXIndexarConSeparadoresPalabras);
      END IF;

      CALL DemeSiguientePalabra(@PalabrasXIndexar, CaracteresValidos, @NuevoIndice, @SiguientePalabra, @NuevoIndice, TamanoMaximoPalabras, 0);
    END WHILE;
  END IF;
  
  IF RetornarResultadosEnSelect = 1 THEN
    SELECT a.IdUsuario, a.Usuario, a.Cedula, a.Nombre, c.IdPalabra, c.Palabra, c.EstaLibre, d.IdPalabraSemejante, d.PalabraSemejante, d.EstaLibre, e.Caracter
    FROM Usuarios a, PalabrasXUsuario b, Palabras c, PalabrasSemejantes d, CaracteresXPalabraSemejante e
    WHERE a.IdUsuario = b.IdUsuario
    AND b.IdPalabra = c.IdPalabra
    AND c.IdPalabraSemejante = d.IdPalabraSemejante
    AND d.IdPalabraSemejante = e.IdPalabraSemejante
    AND a.IdUsuario = UnIdUsuario;
  END IF;
  
  COMMIT;
END;

DELIMITER $$
 
CREATE PROCEDURE IndexarTodosUsuarios(IN CaracteresValidos VARCHAR(500), IN TuplasReemplazos VARCHAR(500), IN SeparadorTuplas VARCHAR(1), IN SeparadorColumnas VARCHAR(1), IN SeparadorPalabras VARCHAR(1), IN TamanoMaximoPalabras INT, IN RetornarResultadosEnSelect BIT(1))
BEGIN
  START TRANSACTION;
   
  CREATE TEMPORARY TABLE UsuariosXIndexar
  SELECT IdUsuario
  FROM Usuarios
  ORDER BY IdUsuario ASC;

  SELECT MIN(IdUsuario) FROM UsuariosXIndexar INTO @IdUsuario;
  
  WHILE ISNULL(@IdUsuario) = 0 DO
    CALL IndexarUsuario(@IdUsuario, CaracteresValidos, TuplasReemplazos, SeparadorTuplas, SeparadorColumnas, SeparadorPalabras, TamanoMaximoPalabras, 0);
    SELECT MIN(IdUsuario) FROM UsuariosXIndexar WHERE IdUsuario > @IdUsuario INTO @IdUsuario;
  END WHILE;
  
  DROP TEMPORARY TABLE UsuariosXIndexar;

  IF RetornarResultadosEnSelect = 1 THEN
    SELECT a.IdUsuario, a.Usuario, a.Cedula, a.Nombre, c.IdPalabra, c.Palabra, c.EstaLibre, d.IdPalabraSemejante, d.PalabraSemejante, d.EstaLibre, e.Caracter
    FROM Usuarios a, PalabrasXUsuario b, Palabras c, PalabrasSemejantes d, CaracteresXPalabraSemejante e
    WHERE a.IdUsuario = b.IdUsuario
    AND b.IdPalabra = c.IdPalabra
    AND c.IdPalabraSemejante = d.IdPalabraSemejante
    AND d.IdPalabraSemejante = e.IdPalabraSemejante;
  END IF;
  
  COMMIT; 
END;

DELIMITER $$
 
CREATE PROCEDURE ValidarCamposUsuario(IN UnUsuario VARCHAR(50), IN UnaCedula VARCHAR(100), IN UnNombre VARCHAR(100), IN CaracteresValidos VARCHAR(500), OUT NumError INT, IN RetornarResultadosEnSelect BIT(1))
BEGIN
  SET NumError = 0;
  CALL DemeSiguientePalabra(UnUsuario, CaracteresValidos, 1, @SiguientePalabra, @NuevoIndice, -1, 0);
  
  IF CHAR_LENGTH(@SiguientePalabra) = 0 THEN
    SET NumError = 1;
  ELSE
    CALL DemeSiguientePalabra(UnaCedula, CaracteresValidos, 1, @SiguientePalabra, @NuevoIndice, -1, 0);

    IF CHAR_LENGTH(@SiguientePalabra) = 0 THEN
      SET NumError = 2;
    ELSE
      CALL DemeSiguientePalabra(UnNombre, CaracteresValidos, 1, @SiguientePalabra, @NuevoIndice, -1, 0);

      IF CHAR_LENGTH(@SiguientePalabra) = 0 THEN
        SET NumError = 3;
      END IF;
    END IF;
  END IF;
  
  IF RetornarResultadosEnSelect = 1 THEN
    SELECT NumError;
  END IF;
END;

DELIMITER $$
 
CREATE PROCEDURE AltaUsuario(IN UnUsuario VARCHAR(50), IN UnaCedula VARCHAR(100), IN UnNombre VARCHAR(100), BitEsAdministrador BIT(1), IN CaracteresValidos VARCHAR(500), IN TuplasReemplazos VARCHAR(500), IN SeparadorTuplas VARCHAR(1), IN SeparadorColumnas VARCHAR(1), IN SeparadorPalabras VARCHAR(1), IN TamanoMaximoPalabras INT, IN RetornarResultadosEnSelect BIT(1))
BEGIN
  START TRANSACTION;
   
  SET @IncrementosNumBaseError = 1000;
  SET @NumBaseError = @IncrementosNumBaseError;

  SET @NumError = 0;
  SELECT MIN(IdUsuario) FROM Usuarios WHERE Usuario = UnUsuario INTO @IdUsuario;
  
  IF ISNULL(@IdUsuario) = 0 THEN
    SET @NumError = @NumBaseError + 1;
  ELSE
    SET @NumBaseError = @NumBaseError + @IncrementosNumBaseError;
    CALL ValidarCamposUsuario(UnUsuario, UnaCedula, UnNombre, CaracteresValidos, @NumError, 0);

    IF @NumError = 0 THEN
      SELECT MAX(IdUsuario) FROM Usuarios INTO @IdUsuario;
  
      IF ISNULL(@IdUsuario) = 1 THEN
        SET @IdUsuario = 0;
      END IF;
    
      SET @IdUsuario = @IdUsuario + 1;

      INSERT INTO Usuarios(IdUsuario, Usuario, Cedula, Nombre, Contrasena, EsAdministrador) VALUES(@IdUsuario, UnUsuario, UnaCedula, UnNombre, '', BitEsAdministrador);
      CALL IndexarUsuario(@IdUsuario, CaracteresValidos, TuplasReemplazos, SeparadorTuplas, SeparadorColumnas, SeparadorPalabras, TamanoMaximoPalabras, 0);
    ELSE
      SET @NumError = @NumBaseError + @NumError;
    END IF;
  END IF;
  
  IF RetornarResultadosEnSelect = 1 THEN
    SELECT @NumError, @IdUsuario;
  END IF;
  
  COMMIT; 
END;

DELIMITER $$
 
CREATE PROCEDURE CambioUsuario(IN UnIdUsuario INT, IN UnUsuario VARCHAR(50), IN UnaCedula VARCHAR(100), IN UnNombre VARCHAR(100), IN BitEsAdministrador BIT(1), BorrarContrasena BIT(1), IN CaracteresValidos VARCHAR(500), IN TuplasReemplazos VARCHAR(500), IN SeparadorTuplas VARCHAR(1), IN SeparadorColumnas VARCHAR(1), IN SeparadorPalabras VARCHAR(1), IN TamanoMaximoPalabras INT, IN RetornarResultadosEnSelect BIT(1))
BEGIN
  START TRANSACTION;

  SET @IncrementosNumBaseError = 1000;
  SET @NumBaseError = @IncrementosNumBaseError;
   
  SET @NumError = 0;
  SELECT MIN(IdUsuario) FROM Usuarios WHERE IdUsuario <> UnIdUsuario AND Usuario = UnUsuario INTO @IdUsuario;
  
  IF ISNULL(@IdUsuario) = 0 THEN
    SET @NumError = @NumBaseError + 1;
  ELSE
    SET @NumBaseError = @NumBaseError + @IncrementosNumBaseError;

    SELECT MIN(IdUsuario) FROM Usuarios WHERE IdUsuario = UnIdUsuario INTO @IdUsuario;
    
    IF ISNULL(@IdUsuario) = 1 THEN
      SET @NumError = @NumBaseError + 1;
    ELSE
      SET @NumBaseError = @NumBaseError + @IncrementosNumBaseError;
      CALL ValidarCamposUsuario(UnUsuario, UnaCedula, UnNombre, CaracteresValidos, @NumError, 0);

      IF @NumError = 0 THEN
        UPDATE Usuarios SET Usuario = UnUsuario, Cedula = UnaCedula, Nombre = UnNombre, EsAdministrador = BitEsAdministrador WHERE IdUsuario = @IdUsuario;
      
        IF BorrarContrasena = 1 THEN
          UPDATE Usuarios SET Contrasena = '' WHERE IdUsuario = @IdUsuario;
        END IF;
      
        CALL IndexarUsuario(@IdUsuario, CaracteresValidos, TuplasReemplazos, SeparadorTuplas, SeparadorColumnas, SeparadorPalabras, TamanoMaximoPalabras, 0);
      ELSE
        SET @NumError = @NumBaseError + @NumError;
      END IF;
    END IF;    
  END IF;
  
  IF RetornarResultadosEnSelect = 1 THEN
    SELECT @NumError;
  END IF;
  
  COMMIT; 
END;

DELIMITER $$
 
CREATE PROCEDURE IndexarNoticia(IN UnIdNoticia INT, IN CaracteresValidos VARCHAR(500), IN TuplasReemplazos VARCHAR(500), IN SeparadorTuplas VARCHAR(1), IN SeparadorColumnas VARCHAR(1), IN SeparadorPalabras VARCHAR(1), IN TamanoMaximoPalabras INT, IN RetornarResultadosEnSelect BIT(1))
BEGIN
  START TRANSACTION;
  
  DELETE FROM PalabrasXNoticiaActiva WHERE IdNoticia = UnIdNoticia;
  CALL LiberarPalabrasNoUtilizadas();
  
  SELECT COUNT(1) FROM NoticiasActivas a, HistoricoNoticias b WHERE a.IdNoticia = b.IdNoticia AND a.IdNoticia = UnIdNoticia AND a.Consecutivo = b.Consecutivo INTO @CantidadNoticias;
  
  IF (@CantidadNoticias = 1) THEN
    SELECT LOWER(CONCAT(b.Titulo, SeparadorPalabras, b.Resumen)) FROM NoticiasActivas a, HistoricoNoticias b WHERE a.IdNoticia = b.IdNoticia AND a.Consecutivo = b.Consecutivo AND a.IdNoticia = UnIdNoticia GROUP BY b.Titulo, b.Resumen INTO @PalabrasXIndexar;

    SET @PalabrasIndexadas = '';

    CALL DemeSiguientePalabra(@PalabrasXIndexar, CaracteresValidos, 1, @SiguientePalabra, @NuevoIndice, TamanoMaximoPalabras, 0);
    
    WHILE CHAR_LENGTH(@SiguientePalabra) > 0 DO
      SET @SiguientePalabraXIndexarConSeparadoresPalabras = CONCAT(SeparadorPalabras, @SiguientePalabra, SeparadorPalabras);
      
      IF POSITION(@SiguientePalabraXIndexarConSeparadoresPalabras IN @PalabrasIndexadas) < 1 THEN
        CALL IndexarPalabra(@SiguientePalabra, TuplasReemplazos, SeparadorTuplas, SeparadorColumnas, 0);
        SELECT MIN(IdPalabra) FROM Palabras WHERE Palabra = @SiguientePalabra INTO @IdPalabra;
        INSERT INTO PalabrasXNoticiaActiva(IdNoticia, IdPalabra) VALUES(UnIdNoticia, @IdPalabra);
        SET @PalabrasIndexadas = CONCAT(@PalabrasIndexadas, @SiguientePalabraXIndexarConSeparadoresPalabras);
      END IF;

      CALL DemeSiguientePalabra(@PalabrasXIndexar, CaracteresValidos, @NuevoIndice, @SiguientePalabra, @NuevoIndice, TamanoMaximoPalabras, 0);
    END WHILE;
  END IF;
  
  IF RetornarResultadosEnSelect = 1 THEN
    SELECT a1.IdNoticia, a2.Titulo, a2.Resumen, c.IdPalabra, c.Palabra, c.EstaLibre, d.IdPalabraSemejante, d.PalabraSemejante, d.EstaLibre, e.Caracter
    FROM NoticiasActivas a1, HistoricoNoticias a2, PalabrasXNoticiaActiva b, Palabras c, PalabrasSemejantes d, CaracteresXPalabraSemejante e
    WHERE a1.IdNoticia = a2.IdNoticia
    AND a1.Consecutivo = a2.Consecutivo
    AND a2.IdNoticia = b.IdNoticia    
    AND b.IdPalabra = c.IdPalabra
    AND c.IdPalabraSemejante = d.IdPalabraSemejante
    AND d.IdPalabraSemejante = e.IdPalabraSemejante
    AND a1.IdNoticia = UnIdNoticia;
  END IF;
  
  COMMIT;
END;

DELIMITER $$
 
CREATE PROCEDURE IndexarTodasNoticias(IN CaracteresValidos VARCHAR(500), IN TuplasReemplazos VARCHAR(500), IN SeparadorTuplas VARCHAR(1), IN SeparadorColumnas VARCHAR(1), IN SeparadorPalabras VARCHAR(1), IN TamanoMaximoPalabras INT, IN RetornarResultadosEnSelect BIT(1))
BEGIN
  START TRANSACTION;
   
  CREATE TEMPORARY TABLE NoticiasXIndexar
  SELECT IdNoticia
  FROM NoticiasActivas
  ORDER BY IdNoticia ASC;

  SELECT MIN(IdNoticia) FROM NoticiasXIndexar INTO @IdNoticia;
  
  WHILE ISNULL(@IdNoticia) = 0 DO
    CALL IndexarNoticia(@IdNoticia, CaracteresValidos, TuplasReemplazos, SeparadorTuplas, SeparadorColumnas, SeparadorPalabras, TamanoMaximoPalabras, 0);
    SELECT MIN(IdNoticia) FROM NoticiasXIndexar WHERE IdNoticia > @IdNoticia INTO @IdNoticia;
  END WHILE;
  
  DROP TEMPORARY TABLE NoticiasXIndexar;

  IF RetornarResultadosEnSelect = 1 THEN
    SELECT a1.IdNoticia, a2.Titulo, a2.Resumen, c.IdPalabra, c.Palabra, c.EstaLibre, d.IdPalabraSemejante, d.PalabraSemejante, d.EstaLibre, e.Caracter
    FROM NoticiasActivas a1, HistoricoNoticias a2, PalabrasXNoticiaActiva b, Palabras c, PalabrasSemejantes d, CaracteresXPalabraSemejante e
    WHERE a1.IdNoticia = a2.IdNoticia
    AND a1.Consecutivo = a2.Consecutivo
    AND a2.IdNoticia = b.IdNoticia    
    AND b.IdPalabra = c.IdPalabra
    AND c.IdPalabraSemejante = d.IdPalabraSemejante
    AND d.IdPalabraSemejante = e.IdPalabraSemejante;
  END IF;
  
  COMMIT; 
END;

DELIMITER $$
 
CREATE PROCEDURE ValidarCamposNoticia(IN UnTitulo VARCHAR(100), IN UnResumen VARCHAR(200), IN UnaURLRelativaNoticia VARCHAR(100), UnIdUsuarioAutor INT, IN CaracteresValidos VARCHAR(500), OUT NumError INT, IN RetornarResultadosEnSelect BIT(1))
BEGIN
  SET NumError = 0;
  CALL DemeSiguientePalabra(UnTitulo, CaracteresValidos, 1, @SiguientePalabra, @NuevoIndice, -1, 0);
  
  IF CHAR_LENGTH(@SiguientePalabra) = 0 THEN
    SET NumError = 1;
  ELSE
    CALL DemeSiguientePalabra(UnResumen, CaracteresValidos, 1, @SiguientePalabra, @NuevoIndice, -1, 0);

    IF CHAR_LENGTH(@SiguientePalabra) = 0 THEN
      SET NumError = 2;
    ELSE
      CALL DemeSiguientePalabra(UnaURLRelativaNoticia, CaracteresValidos, 1, @SiguientePalabra, @NuevoIndice, -1, 0);

      IF CHAR_LENGTH(@SiguientePalabra) = 0 THEN
        SET NumError = 3;
      ELSE
        SELECT MIN(IdUsuario) FROM Usuarios WHERE IdUsuario = UnIdUsuarioAutor INTO @IdUsuario;
  
        IF ISNULL(@IdUsuario) = 1 THEN
          SET NumError = 4;
        END IF;
      END IF;
    END IF;
  END IF;
  
  IF RetornarResultadosEnSelect = 1 THEN
    SELECT NumError;
  END IF;
END;

DELIMITER $$
 
CREATE PROCEDURE AltaNoticia(IN UnaFechaPublicacionNoticia DATE, IN UnTitulo VARCHAR(100), IN UnResumen VARCHAR(200), IN UnaURLRelativaNoticia VARCHAR(100), UnIdUsuarioAutor INT, IN UnIdUsuarioModificoRegistro INT, CaracteresValidos VARCHAR(500), IN TuplasReemplazos VARCHAR(500), IN SeparadorTuplas VARCHAR(1), IN SeparadorColumnas VARCHAR(1), IN SeparadorPalabras VARCHAR(1), IN TamanoMaximoPalabras INT, IN RetornarResultadosEnSelect BIT(1))
BEGIN
  START TRANSACTION;
   
  SET @IncrementosNumBaseError = 1000;
  SET @NumBaseError = @IncrementosNumBaseError;

  CALL ValidarCamposNoticia(UnTitulo, UnResumen, UnaURLRelativaNoticia, UnIdUsuarioAutor, CaracteresValidos, @NumError, 0);

  IF @NumError = 0 THEN
    SELECT MAX(IdNoticia) FROM NoticiasActivas INTO @IdNoticia;
  
    IF ISNULL(@IdNoticia) = 1 THEN
      SET @IdNoticia = 0;
    END IF;
    
    SET @IdNoticia = @IdNoticia + 1;
    SET @Consecutivo = 1;

    INSERT INTO HistoricoNoticias(IdNoticia, Consecutivo, FechaPublicacionNoticia, Titulo, Resumen, URLRelativaNoticia, IdUsuarioAutor, IdUsuarioModificoRegistro, FechaHoraModificacionRegistro)
    VALUES(@IdNoticia, @Consecutivo, UnaFechaPublicacionNoticia, UnTitulo, UnResumen, UnaURLRelativaNoticia, UnIdUsuarioAutor, UnIdUsuarioModificoRegistro, NOW());

    INSERT INTO NoticiasActivas(IdNoticia, Consecutivo) VALUES (@IdNoticia, @Consecutivo);

    CALL IndexarNoticia(@IdNoticia, CaracteresValidos, TuplasReemplazos, SeparadorTuplas, SeparadorColumnas, SeparadorPalabras, TamanoMaximoPalabras, 0);
  ELSE
    SET @NumError = @NumBaseError + @NumError;
  END IF;
  
  IF RetornarResultadosEnSelect = 1 THEN
    SELECT @NumError, @IdNoticia;
  END IF;
  
  COMMIT; 
END;

DELIMITER $$
 
CREATE PROCEDURE CambioNoticia(IN UnIdNoticia INT, IN UnaFechaPublicacionNoticia DATE, IN UnTitulo VARCHAR(100), IN UnResumen VARCHAR(200), IN UnaURLRelativaNoticia VARCHAR(100), UnIdUsuarioAutor INT, IN UnIdUsuarioModificoRegistro INT, CaracteresValidos VARCHAR(500), IN TuplasReemplazos VARCHAR(500), IN SeparadorTuplas VARCHAR(1), IN SeparadorColumnas VARCHAR(1), IN SeparadorPalabras VARCHAR(1), IN TamanoMaximoPalabras INT, IN RetornarResultadosEnSelect BIT(1))
BEGIN
  START TRANSACTION;

  SET @IncrementosNumBaseError = 1000;
  SET @NumBaseError = @IncrementosNumBaseError;
   
  SELECT MIN(IdNoticia) FROM NoticiasActivas WHERE IdNoticia = UnIdNoticia INTO @IdNoticia;
    
  IF ISNULL(@IdNoticia) = 1 THEN
    SET @NumError = @NumBaseError + 1;
  ELSE
    SET @NumBaseError = @NumBaseError + @IncrementosNumBaseError;

    CALL ValidarCamposNoticia(UnTitulo, UnResumen, UnaURLRelativaNoticia, UnIdUsuarioAutor, CaracteresValidos, @NumError, 0);

    IF @NumError = 0 THEN
      SELECT MAX(Consecutivo) FROM HistoricoNoticias WHERE IdNoticia = @IdNoticia INTO @Consecutivo;
  
      IF ISNULL(@Consecutivo) = 1 THEN
        SET @Consecutivo = 0;
      END IF;
    
      SET @Consecutivo = @Consecutivo + 1;

      INSERT INTO HistoricoNoticias(IdNoticia, Consecutivo, FechaPublicacionNoticia, Titulo, Resumen, URLRelativaNoticia, IdUsuarioAutor, IdUsuarioModificoRegistro, FechaHoraModificacionRegistro)
      VALUES(@IdNoticia, @Consecutivo, UnaFechaPublicacionNoticia, UnTitulo, UnResumen, UnaURLRelativaNoticia, UnIdUsuarioAutor, UnIdUsuarioModificoRegistro, NOW());

      UPDATE NoticiasActivas SET Consecutivo = @Consecutivo WHERE IdNoticia = @IdNoticia;

      CALL IndexarNoticia(@IdNoticia, CaracteresValidos, TuplasReemplazos, SeparadorTuplas, SeparadorColumnas, SeparadorPalabras, TamanoMaximoPalabras, 0);
    ELSE
      SET @NumError = @NumBaseError + @NumError;
    END IF;
  END IF;
  
  IF RetornarResultadosEnSelect = 1 THEN
    SELECT @NumError;
  END IF;
  
  COMMIT; 
END;

DELIMITER $$

CREATE PROCEDURE ValidarCamposComentarioNoticia(IN UnNombreComentarista VARCHAR(100), IN UnEmailComentarista VARCHAR(50), IN UnComentario VARCHAR(300), UnIdNoticia INT, UnConsecutivoResponde INT, IN CaracteresValidos VARCHAR(500), OUT NumError INT, IN RetornarResultadosEnSelect BIT(1))
BEGIN
  SET NumError = 0;
  CALL DemeSiguientePalabra(UnComentario, CaracteresValidos, 1, @SiguientePalabra, @NuevoIndice, -1, 0);
  
  IF CHAR_LENGTH(@SiguientePalabra) = 0 THEN
    SET NumError = 1;
  ELSE
    CALL DemeSiguientePalabra(UnNombreComentarista, CaracteresValidos, 1, @SiguientePalabra, @NuevoIndice, -1, 0);

    IF CHAR_LENGTH(@SiguientePalabra) = 0 THEN
      SET NumError = 2;
    ELSE
      CALL DemeSiguientePalabra(UnEmailComentarista, CaracteresValidos, 1, @SiguientePalabra, @NuevoIndice, -1, 0);

      IF CHAR_LENGTH(@SiguientePalabra) = 0 THEN
        SET NumError = 3;
      ELSE
        IF UnConsecutivoResponde >= 1 THEN
          SELECT MIN(Consecutivo) FROM ComentariosNoticias WHERE IdNoticia = UnIdNoticia AND Consecutivo = UnConsecutivoResponde INTO @ConsecutivoResponde;
  
          IF ISNULL(@ConsecutivoResponde) = 1 THEN
            SET NumError = 4;
          END IF;
        END IF;
      END IF;
    END IF;
  END IF;
  
  IF RetornarResultadosEnSelect = 1 THEN
    SELECT NumError;
  END IF;
END;

DELIMITER $$
 
CREATE PROCEDURE AltaComentarioNoticia(IN UnNombreComentarista VARCHAR(100), IN UnEmailComentarista VARCHAR(50), IN UnComentario VARCHAR(300), UnIdNoticia INT, UnConsecutivoResponde INT, UnIdUsuarioInsertoRegistro INT, UnEstatusInicial BIT(2), CaracteresValidos VARCHAR(500), IN RetornarResultadosEnSelect BIT(1))
BEGIN
  START TRANSACTION;
   
  SET @IncrementosNumBaseError = 1000;
  SET @NumBaseError = @IncrementosNumBaseError;

  CALL ValidarCamposComentarioNoticia(UnNombreComentarista, UnEmailComentarista, UnComentario, UnIdNoticia, UnConsecutivoResponde, CaracteresValidos, @NumError, 0);

  IF @NumError = 0 THEN
    SELECT MAX(Consecutivo) FROM ComentariosNoticias WHERE IdNoticia = UnIdNoticia INTO @ConsecutivoComentario;
  
    IF ISNULL(@ConsecutivoComentario) = 1 THEN
      SET @ConsecutivoComentario = 0;
    END IF;
    
    SET @ConsecutivoComentario = @ConsecutivoComentario + 1;
    SET @ConsecutivoEstatus = 1;

    INSERT INTO ComentariosNoticias(IdNoticia, Consecutivo, FechaHoraEnvio, NombreComentarista, EmailComentarista, Comentario)
    VALUES(UnIdNoticia, @ConsecutivoComentario, NOW(), UnNombreComentarista, UnEmailComentarista, UnComentario);
    
    IF UnConsecutivoResponde >= 1 THEN
      SET @ConsecutivoRespuesta = @ConsecutivoComentario;
      
      INSERT INTO RespuestasComentariosNoticias(IdNoticia, ConsecutivoResponde, ConsecutivoRespuesta)
      VALUES(UnIdNoticia, UnConsecutivoResponde, @ConsecutivoRespuesta);
    END IF;
    
    INSERT INTO HistoricoEstatusComentariosNoticias(IdNoticia, ConsecutivoComentario, ConsecutivoEstatus, Estatus, FechaHoraModificacionEstatus, IdUsuarioModificoEstatus)
    VALUES(UnIdNoticia, @ConsecutivoComentario, @ConsecutivoEstatus, UnEstatusInicial, NOW(), UnIdUsuarioInsertoRegistro);

    INSERT INTO EstatusComentariosNoticias(IdNoticia, ConsecutivoComentario, ConsecutivoEstatus)
    VALUES(UnIdNoticia, @ConsecutivoComentario, @ConsecutivoEstatus);
  ELSE
    SET @NumError = @NumBaseError + @NumError;
  END IF;
  
  IF RetornarResultadosEnSelect = 1 THEN
    SELECT @NumError, @ConsecutivoComentario;
  END IF;
  
  COMMIT; 
END;

DELIMITER $$
 
CREATE PROCEDURE CambioEstatusComentarioNoticia(UnIdNoticia INT, UnConsecutivoComentario INT, UnIdUsuarioModificoRegistro INT, UnEstatus BIT(2), IN RetornarResultadosEnSelect BIT(1))
BEGIN
  START TRANSACTION;
   
  SET @NumError = 0;
     
  SET @IncrementosNumBaseError = 1000;
  SET @NumBaseError = @IncrementosNumBaseError;
  
  SELECT MIN(IdNoticia) FROM ComentariosNoticias WHERE IdNoticia = UnIdNoticia AND Consecutivo = UnConsecutivoComentario INTO @IdNoticia;
    
  IF ISNULL(@IdNoticia) = 1 THEN
    SET @NumError = @NumBaseError + 1;
  ELSE
     SELECT MAX(ConsecutivoEstatus) FROM EstatusComentariosNoticias WHERE IdNoticia = UnIdNoticia AND ConsecutivoComentario = UnConsecutivoComentario INTO @ConsecutivoEstatus;
  
      IF ISNULL(@ConsecutivoEstatus) = 1 THEN
        SET @ConsecutivoEstatus = 0;
      END IF;
    
      SET @ConsecutivoEstatus = @ConsecutivoEstatus + 1;
    
      INSERT INTO HistoricoEstatusComentariosNoticias(IdNoticia, ConsecutivoComentario, ConsecutivoEstatus, Estatus, FechaHoraModificacionEstatus, IdUsuarioModificoEstatus)
      VALUES(UnIdNoticia, UnConsecutivoComentario, @ConsecutivoEstatus, UnEstatus, NOW(), UnIdUsuarioModificoRegistro);

      UPDATE EstatusComentariosNoticias SET ConsecutivoEstatus = @ConsecutivoEstatus
      WHERE IdNoticia = UnIdNoticia
      AND ConsecutivoComentario = UnConsecutivoComentario;
  END IF;
    
  IF RetornarResultadosEnSelect = 1 THEN
    SELECT @NumError;
  END IF;
    
  COMMIT; 
END;
