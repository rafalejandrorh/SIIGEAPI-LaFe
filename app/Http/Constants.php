<?php

namespace App\Http;

class Constants
{
    // Nombre de la Aplicación
    const applicationName = 'SIIGEAPI';
    const applicationVersion = '2.0.1';

    // Id de Venezuela en Nomenclador de Geografia
    const idVenezuelaNomenclador = 1060223;

    //Timezone (Hora Local)
    const Ubicacion = 'America/Caracas';

    //API KEY GOOGLE MAPS
    const GOOGLE_API_KEY = 'AIzaSyC-Njn9mrL79sna5BrLTrzAN2DBiYIWWZI';

    // Key de JWT
    const JWT_KEY = 'dfhsdfg32dfhcs4xgsrrsdry46';

    // ID de acciones de Trazas
    const REGISTRO = 1;
    const ACTUALIZACION = 2;
    const ELIMINACION = 3;
    const VISUALIZACION = 4;
    const BUSQUEDA = 5;
    const PDF = 6;
    const RESTAURACION = 7;
    const DESCARGA = 8;
    const PAPELERA = 9;
    const INGRESO_MODULO = 10;

    /////// Constantes de API ///////

    // Tipos de Solicitud
    const APP = 'app';
    const SERVICE = 'service';

    // Code´s Response
    const HTTP_CODE_OK = 200;
    const HTTP_CPDE_CREATED = 201;
    const HTTP_CODE_ACCEPTED = 202;
    const HTTP_CODE_NO_AUTHORITATIVE_INFORMATION = 203;
    const HTTP_CODE_NO_CONTENT = 204;
    const HTTP_CODE_RESET_CONTENT = 205;
    const HTTP_CODE_PARTIAL_CONTENT = 206;
    const HTTP_CODE_MULTI_STATUS = 207;
    const HTTP_CODE_MULTIPLE_CHOICE = 300;
    const HTTP_CODE_MOVED_PERMANENTLY = 301;
    const HTTP_CODE_SEE_OTHER = 303;
    const HTTP_CODE_NOT_MODIFIED = 304;
    const HTTP_CODE_TEMPORARY_REDIRECT = 307;
    const HTTP_CODE_PERMANENT_REDIRECT = 308;
    const HTTP_CODE_BAD_REQUEST = 400;
    const HTTP_CODE_UNAUTHORIZED = 401;
    const HTTP_CODE_FORBIDDEN = 403;
    const HTTP_CODE_NOT_FOUND = 404;
    const HTTP_CODE_METHOD_NOT_ALLOWED = 405;
    const HTTP_CODE_NOT_ACCEPTABLE = 406;
    const HTTP_CODE_PROXY_AUTHENTICATION_REQUIRED = 407;
    const HTTP_CODE_LENGTH_REQUIRED = 411;
    const HTTP_CODE_PRECONDITION_FAILED = 412;
    const HTTP_CODE_INTERNAL_SERVER_ERROR = 500;
    const HTTP_CODE_NOT_IMPLEMENTED = 501;
    const HTTP_CODE_BAD_GATEWAY = 502;

    const HTTP_DESCRIPTION_NOT_FOUND = 'Resource Not Found';
    const HTTP_DESCRIPTION_BAD_REQUEST = 'Bad Request';
    const HTTP_DESCRIPTION_FORBIDDEN = 'Forbidden';
    const HTTP_DESCRIPTION_UNAUTHORIZED = 'Unauthorized';
    const HTTP_DESCRIPTION_OK = 'Ok';

    const HTTP_MESSAGE_FORBIDDEN = 'You don`t have permission to access this service';
    const HTTP_MESSAGE_OK = 'Operation Success';

    // Description´s Response
    // Ok (Login)
    const DESCRIPTION_OK_AUTH = 'Authentication Ok';

    // Nok (Logout)
    const DESCRIPTION_ERROR_AUTH = 'Authentication Nok';

    // Nok (Error en el servicio consultado)
    const DESCRIPTION_ERROR_SERVICE = 'Service Nok';

    // Ok (Token Ok)
    const DESCRIPTION_OK_TOKEN = 'Token Ok';

    // Nok (Error por Token sin Bearer)
    const DESCRIPTION_ERROR_NOT_TOKEN_BEARER = 'No Token Bearer';

    // Nok (Error por Token Expirado)
    const DESCRIPTION_ERROR_TOKEN_EXPIRE = 'Token Expire';

    // Nok (Error por Token Incorrecto)
    const DESCRIPTION_ERROR_TOKEN = 'Token Nok';

    // Nok (Error por no Colocar Token)
    const DESCRIPTION_ERROR_NOT_TOKEN = 'No Token';

    // Nok (No existe el Token)
    const DESCRIPTION_ERROR_NOT_TOKEN_EXISTS = 'No Token Exists';

    // Nok (No existe el Token)
    const DESCRIPTION_ERROR_NOT_TOKEN_MATCH = 'Token Mismatch';

    // Nok (Token Inactivo)
    const DESCRIPTION_ERROR_INACTIVE_TOKEN = 'Inactive Token';

    // Nok (Usuario no tiene accesso a la APP)
    const DESCRIPTION_ERROR_NOT_ACCESS_APP = 'No Access to the App';

    // Nok (Solicitud Inválida)
    const DESCRIPTION_ERROR_INVALID_REQUEST = 'Invalid Request';

    // Nok (Servicio Inactivo)
    const DESCRIPTION_ERROR_INACTIVE_SERVICE = 'Inactive Service';

    // Nok (Acción no permitida en el servicio)
    const DESCRIPTION_ERROR_UNAUTHORIZED_ACTION = 'Unauthorized Action';

    // Nok (Sin Autorización)
    const DESCRIPTION_ERROR_UNAUTHORIZED = 'Unauthorized';

    // Nok (No Autorizado por falta de Header X-Entity)
    const DESCRIPTION_ERROR_NOT_HEADER_ENTITY = 'No Header Entity';

    // Nok (No Existe Entity)
    const DESCRIPTION_ERROR_NOT_ENTITY_EXISTS = 'No Entity';

    // Nok (No Existe Entity)
    const DESCRIPTION_ERROR_NOT_HEADER_REQUEST_TYPE = 'No Header Request-Type';

    // Nok (No Existe Entity)
    const DESCRIPTION_ERROR_NOT_REQUEST_TYPE_ALLOWED = 'Request-Type Not Allowed';
    
    // Nok (Usuario Inactivo)
    const DESCRIPTION_ERROR_INACTIVE_USER = 'Inactive User';
}

?>