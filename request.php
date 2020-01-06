<?php

interface Request {
  public function response($status, $error, $data = null);
}
