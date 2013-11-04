<?php

namespace common\model;

interface Observer {
	public function notify(\user\model\User $user);
}