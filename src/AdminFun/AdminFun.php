<?php

/*
 * This file is the main class of AdminFun.
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

namespace AdminFun;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\command\CommandExecutor;
use pocketmine\Player;
use pocketmine\block\Block;
use pocketmine\math\Vector3;

use AdminFun\commands\Commands;
use AdminFun\listeners\ConfuseListener;
use AdminFun\listeners\FreezeListener;
use AdminFun\listeners\LockListener;
use AdminFun\tasks\DroppartyTask;

class AdminFun extends PluginBase{
  private $frozen = [];
  private $confuse = [];
  
  public function onEnable(){
    if(!is_dir($this->getDataFolder())){
      mkdir($this->getDataFolder());
    }
    $this->saveDefaultConfig();
    $this->reloadConfig();
    $this->dpdata = new Config($this->getDataFolder()."dpdata.yml", Config::YAML, array());
    $this->getCommand("adminfun")->setExecutor(new Commands($this));
    $this->getServer()->getPluginManager()->registerEvents(new ConfuseListener($this), $this);
    $this->getServer()->getPluginManager()->registerEvents(new FreezeListener($this), $this);
    $this->getLogger()->info("§aLoaded Successfully!");
  }
  
  public function onDisable(){
    unlink($this->getDataFolder()."dpdata.yml");
  }
  
  //FREEZE API
  
  public function isFrozen(Player $player){
    return in_array($player->getName(), $this->frozen);
  }
  public function freeze(Player $player){
    $this->frozen[$player->getName()] = $player->getName();
  } 
  public function unfreeze(Player $player){
    unset($this->frozen[$player->getName()]);
  }
  
  //CONFUSE API
  
  public function confuse(Player $player){
    $this->confuse[$player->getName()] = $player->getName();
  }
  public function unConfuse(Player $player){
    unset($this->confuse[$player->getName()]);
  }
  public function isConfused(Player $player){
    return in_array($player->getName(), $this->confuse);
  }
  
  //DROPPARTY API
  
  public function getDpdata(){
    return $this->dpdata;
  }
  
  public function startDropparty(Player $player, $item, $x, $y, $z, $world, $seconds){
      $this->getServer()->getScheduler()->scheduleRepeatingTask($task = new DroppartyTask($this, $player, $item, $x, $y, $z, $world, $seconds), 10);
      $player->getLevel()->setBlock(new Vector3($x+2, $y-1, $z), new Block(89));
      $player->getLevel()->setBlock(new Vector3($x-2, $y-1, $z), new Block(89));
      $player->getLevel()->setBlock(new Vector3($x, $y-1, $z+2), new Block(89));
      $player->getLevel()->setBlock(new Vector3($x, $y-1, $z-2), new Block(89));
      $this->dpdata = new Config($this->getDataFolder()."dpdata.yml", Config::YAML, array());
      $t = $this->dpdata->getAll();
      $t[$player->getName()]["id"] = $task->getTaskId();
      $t[$player->getName()]["times"] = 1;
      $this->dpdata->setAll($t);
      $this->dpdata->save();
  }
  
  public function isDroppartyHolding(Player $player){
    $t = $this->dpdata->getAll();
    return isset($t[$player->getName()]);
  }
  
  public function stopDropparty(Player $player){
    $t = $this->dpdata->getAll();
    unset($t[$player->getName()]);
    $this->dpdata->setAll($t);
    $this->dpdata->save();
  }
  
}
?>
