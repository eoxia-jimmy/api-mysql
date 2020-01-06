<?php

define('ROUTES', [
  'login',
  'subscribe',
  'servers',
  'join',
  'leave'
]);

define('API_ERRORS', [
  'ROUTE_NOT_FOUND'
]);

define('SUBSCRIBE_ERRORS', [
  'EMPTY_EMAIL_OR_USERNAME_PASSWORD',
  'EMAIL_INVALID'
]);

define('LOGIN_ERRORS', [
  'EMPTY_EMAIL_OR_PASSWORD',
  'EMAIL_OR_PASSWORD_INCORRECT'
]);

define('SERVERS_ERRORS', [
  'EMPTY_USER_ID',
]);

define('JOIN_ERRORS', [
  'EMPTY_USER_OR_URL_OR_TOKEN',
  'ALREADY_JOINED',
  'TOKEN_ERROR',
  'CURL_TIMEOUT',
  'FORBIDDEN'
]);

define('LEAVE_ERRORS', [
  'EMPTY_ID',
]);
