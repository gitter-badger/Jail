<?php
/*
 * This file is a part of Jail.
 * Copyright (C) 2016 hoyinm14mc
 *
 * Jail is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jail is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jail. If not, see <http://www.gnu.org/licenses/>.
 */

namespace hoyinm14mc\jail\commands;

use hoyinm14mc\jail\base\BaseCommand;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class JailCommand extends BaseCommand
{

    public function onCommand(CommandSender $issuer, Command $cmd, $label, array $args)
    {
        switch ($cmd->getName()) {
            case "jail":
                if (count($args) < 3) {
                    return false;
                }
                if ($issuer->hasPermission("jail.command.jail") !== true) {
                    $issuer->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
                    return true;
                }
                $target = $this->getPlugin()->getServer()->getPlayer($args[0]);
                if ($target === null) {
                    $issuer->sendMessage($this->getPlugin()->colorMessage("&cPlayer does not exist"));
                    return true;
                }
                $jail = $args[1];
                $minutes = $args[2];
                if (isset($args[3]) !== false) {
                    $reason = $this->getReason($args);
                }
                if ($this->getPlugin()->jailExists($jail) !== true) {
                    $issuer->sendMessage($this->getPlugin()->colorMessage("&cJail doesn't exist!"));
                    return true;
                }
                if ($minutes != "-i" && (is_numeric($minutes) !== true || $minutes > 6000)) {
                    $issuer->sendMessage($this->getPlugin()->colorMessage("&cInvalid time!"));
                    return true;
                }
                if ($this->getPlugin()->isJailed($target->getName()) !== false) {
                    $issuer->sendMessage($this->getPlugin()->colorMessage("&cTarget is already jailed!"));
                    return true;
                }
                if ($this->getPlugin()->jail($target, $jail, ($minutes == "-i" ? -1 : $minutes), (isset($args[3]) ? $reason : "no reason")) !== false) {
                    $issuer->sendMessage($this->getPlugin()->colorMessage("&aYou jailed " . $target->getName() . " for " . ($minutes == "-i" ? "intinite time" : ($minutes > 1 ? $minutes . " minutes" : $minutes . " minute")) . "!"));
                }
                return true;
                break;
        }
    }

    private function getReason(array $msg): string
    {
        unset ($msg[0]);
        unset ($msg[1]);
        unset ($msg[2]);
        return implode(" ", $msg);
    }

}

?>