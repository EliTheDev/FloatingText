<?php

declare(strict_types=1);

namespace eli\floatingtext\libs\simplepackethandler;

use InvalidArgumentException;
use eli\floatingtext\libs\simplepackethandler\interceptor\IPacketInterceptor;
use eli\floatingtext\libs\simplepackethandler\interceptor\PacketInterceptor;
use eli\floatingtext\libs\simplepackethandler\monitor\IPacketMonitor;
use eli\floatingtext\libs\simplepackethandler\monitor\PacketMonitor;
use pocketmine\event\EventPriority;
use pocketmine\plugin\Plugin;

final class SimplePacketHandler{

	public static function createInterceptor(Plugin $registerer, int $priority = EventPriority::NORMAL, bool $handle_cancelled = false) : IPacketInterceptor{
		if($priority === EventPriority::MONITOR){
			throw new InvalidArgumentException("Cannot intercept packets at MONITOR priority");
		}
		return new PacketInterceptor($registerer, $priority, $handle_cancelled);
	}

	public static function createMonitor(Plugin $registerer, bool $handle_cancelled = false) : IPacketMonitor{
		return new PacketMonitor($registerer, $handle_cancelled);
	}
}