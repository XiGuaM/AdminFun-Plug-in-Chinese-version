<?php

/*
 * This file is a part of AdminFun.
 * Copyright (C) 2015  CyberCube-HK
 *
 * AdminFun is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * AdminFun is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with AdminFun.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace AdminFun\tasks;

use pocketmine\scheduler\PluginTask;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\level\sound\PopSound;
use pocketmine\Player;

use AdminFun\AdminFun;

class DroppartyTask extends PluginTask{
  public $plugin;
  public $player;
  public $item;
  public $x;
  public $y;
  public $z;
  public $world;
  public $seconds;
  
  public function __construct(AdminFun $plugin, Player $player, $item, $x, $y, $z, $world, $seconds){
    $this->plugin = $plugin;
    parent::__construct($plugin);
    $this->player = $player;
    $this->item = $item;
    $this->x = $x;
    $this->y = $y;
    $this->z = $z;
    $this->world = $world;
    $this->seconds = $seconds;
  }
  
  public function onRun($tick){
    $t = $this->plugin->getDpdata()->getAll();
    if($t[$this->player->getName()]["times"] !== $this->seconds*2){
      $this->world->dropItem(new Vector3($this->x, $this->y+2, $this->z), new Item($this->item));
      $this->world->addSound(new PopSound(new Vector3($this->x, $this->y, $this->z)));
      $first = round($t[$this->player->getName()]["times"] / 2);
      $second = $this->seconds - $first;
      $this->player->sendTip("§l§bDropParty §r§atime left:\n§e".$second."§d seconds");
      $t[$this->player->getName()]["times"] = $t[$this->player->getName()]["times"] + 1;
      $this->plugin->getDpdata()->setAll($t);
      $this->plugin->getDpdata()->save();
    }else{
      $this->plugin->stopDropparty($this->player);
      $this->plugin->getServer()->broadcastMessage("§6DropParty §aby §b".$this->player->getName()." §aends!");
      if($this->stopped === null){
        $this->stopped = true;
      }
    }
  }
  
}
?>
