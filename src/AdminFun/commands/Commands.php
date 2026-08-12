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

namespace AdminFun\commands;

use AdminFun\commands\BaseCommand;

use pocketmine\Player;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\level\Explosion;
use pocketmine\level\Position;
use pocketmine\level\sound\LaunchSound;
use pocketmine\math\Vector3;

class Commands extends BaseCommand{
  public function onCommand(CommandSender $issuer, Command $cmd, $label, array $args){
    // 如果没有输入子命令
    if(!isset($args[0])){
      $issuer->sendMessage("§c使用方法: /逗人 <子命令> [参数...]\n§c使用 /逗人 帮助 查看所有命令");
      return true;
    }
    
    switch($args[0]){
      // 公告
      case "announce":
      case "公告":
        if($issuer->hasPermission("adminfun.command") || $issuer->hasPermission("adminfun.command.announce")){
          if(count($args) > 1){
            unset($args[0]);
            $msg = implode(" ", $args);
            $this->getPlugin()->getServer()->broadcastMessage(str_replace(array("&", "%msg%"), array("§", $msg), $this->getPlugin()->getConfig()->get("announce-format")));
            return true;
          }else{
            $issuer->sendMessage("§c使用方法: /逗人 公告 <消息>");
            return true;
          }
        }else{
          $issuer->sendMessage("§c你没有权限使用此命令！");
          return true;
        }
      break;
      
      // 假装上帝
      case "bgod":
      case "假装上帝":
        if($issuer->hasPermission("adminfun.command") || $issuer->hasPermission("adminfun.command.bgod")){
          if(count($args) > 1){
            unset($args[0]);
            $msg = implode(" ", $args);
            $this->getPlugin()->getServer()->broadcastMessage(str_replace(array("&", "%msg%"), array("§", $msg), $this->getPlugin()->getConfig()->get("bgod-format")));
            return true;
          }else{
            $issuer->sendMessage("§c使用方法: /逗人 假装上帝 <消息>");
            return true;
          }
        }else{
          $issuer->sendMessage("§c你没有权限使用此命令！");
          return true;
        }
      break;
      
      // 假装HIM
      case "bherobrine":
      case "假装HIM":
        if($issuer->hasPermission("adminfun.command") || $issuer->hasPermission("adminfun.command.bherobrine")){
          if(count($args) > 1){
            unset($args[0]);
            $msg = implode(" ", $args);
            $this->getPlugin()->getServer()->broadcastMessage(str_replace(array("&", "%msg%"), array("§", $msg), $this->getPlugin()->getConfig()->get("bherobrine-format")));
            return true;
          }else{
            $issuer->sendMessage("§c使用方法: /逗人 假装HIM <消息>");
            return true;
          }
        }else{
          $issuer->sendMessage("§c你没有权限使用此命令！");
          return true;
        }
      break;
      
      // 广播
      case "broadcast":
      case "广播":
        if($issuer->hasPermission("adminfun.command") || $issuer->hasPermission("adminfun.command.broadcast")){
          if(count($args) > 1){
            unset($args[0]);
            $msg = implode(" ", $args);
            $this->getPlugin()->getServer()->broadcastMessage(str_replace("&", "§", $msg));
            return true;
          }else{
            $issuer->sendMessage("§c使用方法: /逗人 广播 <消息..>");
            return true;
          }
        }else{
          $issuer->sendMessage("§c你没有权限使用此命令！");
          return true;
        }
      break;
      
      // 点火
      case "burn":
      case "点火":
        if($issuer->hasPermission("adminfun.command") || $issuer->hasPermission("adminfun.command.burn")){
          if(isset($args[1]) && isset($args[2])){
            $target = $this->getPlugin()->getServer()->getPlayer($args[1]);
            if($target !== null){
              if(is_numeric($args[2])){
                $target->setOnFire($args[2]);
                $issuer->sendMessage("§a".$target->getName()." 已着火！");
                $target->sendMessage("§e需要灭火器吗？ §m§l哈哈");
                return true;
              }else{
                $issuer->sendMessage("§c无效的时间！");
                return true;
              }
            }else{
              $issuer->sendMessage("§c无效的目标！");
              return true;
            }
          }else{
            $issuer->sendMessage("§c使用方法: /逗人 点火 <玩家> <秒数>");
            return true;
          }
        }else{
          $issuer->sendMessage("§c你没有权限使用此命令！");
          return true;
        }
      break;
      
      // 迷惑
      case "confuse":
      case "迷惑":
        if($issuer->hasPermission("adminfun.command") || $issuer->hasPermission("adminfun.command.confuse")){
          if(isset($args[1])){
            $target = $this->getPlugin()->getServer()->getPlayer($args[1]);
            if($target !== null){
              if($this->getPlugin()->isConfused($target) !== true){
                $this->getPlugin()->confuse($target);
                $issuer->sendMessage("§a你迷惑了 ".$target->getName()."！");
                return true;
              }else{
                $this->getPlugin()->unConfuse($target);
                $issuer->sendMessage("§a你解除了 ".$target->getName()." 的迷惑状态！");
                return true;
              }
            }else{
              $issuer->sendMessage("§c无效的目标！");
              return true;
            }
          }else{
            $issuer->sendMessage("§c使用方法: /逗人 迷惑 <玩家>");
            return true;
          }
        }else{
          $issuer->sendMessage("§c你没有权限使用此命令！");
          return true;
        }
      break;
      
      // 控制台
      case "console":
      case "控制台":
        if($issuer->hasPermission("adminfun.command") || $issuer->hasPermission("adminfun.command.console")){
          if(count($args) > 1){
            unset($args[0]);
            $msg = implode(" ", $args);
            $this->getPlugin()->getServer()->broadcastMessage("§o§7[控制台: ".$msg."§7]");
            return true;
          }else{
            $issuer->sendMessage("§c使用方法: /逗人 控制台 <消息>");
            return true;
          }
        }else{
          $issuer->sendMessage("§c你没有权限使用此命令！");
          return true;
        }
      break;
      
      // 爆炸
      case "explode":
      case "爆炸":
        if($issuer->hasPermission("adminfun.command") || $issuer->hasPermission("adminfun.command.explode")){
          if($issuer instanceof Player){
            if(isset($args[1])){
              if(is_numeric($args[1])){
                if(isset($args[2])){
                  $target = $this->getPlugin()->getServer()->getPlayer($args[2]);
                  if($target !== null){
                    $explosion = new Explosion(new Position($target->x, $target->y, $target->z, $target->getLevel()), $args[1]);
                    $explosion->explode();
                    $issuer->sendMessage("§e§l爆炸！！");
                    return true;
                  }else{
                    $issuer->sendMessage("§c无效的目标！");
                    return true;
                  }
                }else{
                  $explosion = new Explosion(new Position($issuer->x, $issuer->y, $issuer->z, $issuer->getLevel()), $args[1]);
                  $explosion->explode();
                  $issuer->sendMessage("§e§l爆炸！！");
                  return true;
                }
              }else{
                $issuer->sendMessage("§c无效的半径！");
                return true;
              }
            }else{
              $issuer->sendMessage("§c使用方法: /逗人 爆炸 <半径> <玩家>");
              return true;
            }
          }else{
            $issuer->sendMessage("§c此命令只能在游戏内使用！");
            return true;
          }
        }else{
          $issuer->sendMessage("§c你没有权限使用此命令！");
          return true;
        }
      break;
      
      // 假加入
      case "fakejoin":
      case "假加入":
        if($issuer->hasPermission("adminfun.command") || $issuer->hasPermission("adminfun.command.fakejoin")){
          if(isset($args[1])){
            $this->getPlugin()->getServer()->broadcastMessage(str_replace(array("&", "%name%"), array("§", $args[1]), $this->getPlugin()->getConfig()->get("fakejoin-format")));
            return true;
          }else{
            $this->getPlugin()->getServer()->broadcastMessage(str_replace(array("&", "%name%"), array("§", $issuer->getName()), $this->getPlugin()->getConfig()->get("fakejoin-format")));
            return true;
          }
        }else{
          $issuer->sendMessage("§c你没有权限使用此命令！");
          return true;
        }
      break;
      
      // 假OP
      case "fakeop":
      case "假OP":
        if($issuer->hasPermission("adminfun.command") || $issuer->hasPermission("adminfun.command.fakeop")){
          if(isset($args[1])){
            $target = $this->getPlugin()->getServer()->getPlayer($args[1]);
            if($target !== null){
              $target->sendMessage("§7你现在是OP了！");
              $issuer->sendMessage("§3你假OP了 ".$target->getName()."！");
              return true;
            }else{
              $issuer->sendMessage("§c无效的目标！");
              return true;
            }
          }else{
            $issuer->sendMessage("§c使用方法: /逗人 假OP <玩家>");
            return true;
          }
        }else{
          $issuer->sendMessage("§c你没有权限使用此命令！");
          return true;
        }
      break;
      
      // 假退出
      case "fakequit":
      case "假退出":
        if($issuer->hasPermission("adminfun.command") || $issuer->hasPermission("adminfun.command.fakequit")){
          if(isset($args[1])){
            $this->getPlugin()->getServer()->broadcastMessage(str_replace(array("&", "%name%"), array("§", $args[1]), $this->getPlugin()->getConfig()->get("fakequit-format")));
            return true;
          }else{
            $this->getPlugin()->getServer()->broadcastMessage(str_replace(array("&", "%name%"), array("§", $issuer->getName()), $this->getPlugin()->getConfig()->get("fakequit-format")));
            return true;
          }
        }else{
          $issuer->sendMessage("§c你没有权限使用此命令！");
          return true;
        }
      break;
      
      // 帮助
      case "help":
      case "帮助":
        if($issuer->hasPermission("adminfun.command") || $issuer->hasPermission("adminfun.command.help")){
          if(!isset($args[1])){
            $issuer->sendMessage("§2显示帮助页面 §6(1/3)");
            $issuer->sendMessage("§l§b- §r§f/逗人 公告 <消息..>");
            $issuer->sendMessage("§l§b- §r§f/逗人 假装上帝 <消息..>");
            $issuer->sendMessage("§l§b- §r§f/逗人 假装HIM <消息..>");
            $issuer->sendMessage("§l§b- §r§f/逗人 广播 <消息..>");
            $issuer->sendMessage("§l§b- §r§f/逗人 点火 <玩家> <秒数>");
            $issuer->sendMessage("§l§b- §r§f/逗人 迷惑 <玩家>");
            $issuer->sendMessage("§l§b- §r§f/逗人 控制台 <消息..>");
            $issuer->sendMessage("§l§b- §r§f/逗人 爆炸 <半径> <玩家>");
            return true;
          }else{
            if(is_numeric($args[1])){
              switch($args[1]){
                case 1:
                  $this->getPlugin()->getServer()->dispatchCommand($issuer, "逗人 帮助");
                  return true;
                break;
                case 2:
                  $issuer->sendMessage("§a显示帮助页面 §6(2/3)");
                  $issuer->sendMessage("§l§b- §r§f/逗人 假加入 <名字>");
                  $issuer->sendMessage("§l§b- §r§f/逗人 假OP <玩家>");
                  $issuer->sendMessage("§l§b- §r§f/逗人 假退出 <名字>");
                  $issuer->sendMessage("§l§b- §r§f/逗人 冻结 <玩家>");
                  $issuer->sendMessage("§l§b- §r§f/逗人 帮助 <1|2|3>");
                  $issuer->sendMessage("§l§b- §r§f/逗人 最大血量 <血量>");
                  $issuer->sendMessage("§l§b- §r§f/逗人 伪装聊天 <玩家> <消息..>");
                  $issuer->sendMessage("§l§b- §r§f/逗人 随机传送 <整蛊|安全> <玩家>");
                  return true;
                break;
                case 3:
                  $issuer->sendMessage("§a显示帮助页面 §6(3/3)");
                  $issuer->sendMessage("§l§b- §r§f/逗人 重载");
                  $issuer->sendMessage("§l§b- §r§f/逗人 火箭 <玩家>");
                  $issuer->sendMessage("§l§b- §r§f/逗人 刷屏广播 <消息..>");
                  $issuer->sendMessage("§l§b- §r§f/逗人 交换 <玩家1> <玩家2>");
                  $issuer->sendMessage("§l§b- §r§f/逗人 虚空 <玩家>");
                  return true;
                break;
              }
            }else{
              $this->getPlugin()->getServer()->dispatchCommand($issuer, "逗人 帮助");
              return true;
            }
          }
        }else{
          $issuer->sendMessage("§c你没有权限使用此命令！");
          return true;
        }
      break;
      
      // 冻结
      case "freeze":
      case "冻结":
        if($issuer->hasPermission("adminfun.command") || $issuer->hasPermission("adminfun.command.freeze")){
          if(isset($args[1])){
            $target = $this->getPlugin()->getServer()->getPlayer($args[1]);
            if($target !== null){
              if($this->getPlugin()->isFrozen($target) !== true){
                $this->getPlugin()->freeze($target);
                $issuer->sendMessage($target->getName()." §a已被冻结！");
                $target->sendMessage("§e你已被 §b冻结§e！");
                return true;
              }else{
                $this->getPlugin()->unfreeze($target);
                $issuer->sendMessage($target->getName()." §a已被解冻！");
                $target->sendMessage("§e你已被 §c解冻§e！");
                return true;
              }
            }else{
              $issuer->sendMessage("§c无效的目标！");
              return true;
            }
          }else{
            $issuer->sendMessage("§c使用方法: /逗人 冻结 <玩家>");
            return true;
          }
        }else{
          $issuer->sendMessage("§c你没有权限使用此命令！");
          return true;
        }
      break;
      
      // 最大血量
      case "maxhealth":
      case "最大血量":
        if($issuer->hasPermission("adminfun.command") || $issuer->hasPermission("adminfun.command.morehealth")){
          if($issuer instanceof Player){
            if(isset($args[1])){
              if(is_numeric($args[1])){
                $issuer->setMaxHealth($args[1] * 2);
                $issuer->setHealth($issuer->getMaxHealth());
                $issuer->sendMessage("§a已将 ".$args[1]." 颗心设为你最大血量！\n§a+ 已为你回满血！");
                return true;
              }else{
                $issuer->sendMessage("§c无效的心数！");
                return true;
              }
            }else{
              $issuer->sendMessage("§c使用方法: /逗人 最大血量 <血量>");
              return true;
            }
          }else{
            $issuer->sendMessage("§c此命令只能在游戏内使用！");
            return true;
          }
        }else{
          $issuer->sendMessage("§c你没有权限使用此命令！");
          return true;
        }
      break;
      
      // 伪装聊天
      case "playerchat":
      case "伪装聊天":
        if($issuer->hasPermission("adminfun.command") || $issuer->hasPermission("adminfun.command.playerchat")){
          if(isset($args[1]) && count($args) > 2){
            $target = str_replace("&", "§", $args[1]);
            unset($args[1]);
            unset($args[0]);
            $msg = implode(" ", $args);
            $this->getPlugin()->getServer()->broadcastMessage(str_replace(array("&", "%name%", "%msg%"), array("§", $target, $msg), $this->getPlugin()->getConfig()->get("playerchat-format")));
            return true;
          }else{
            $issuer->sendMessage("§c使用方法: /逗人 伪装聊天 <玩家> <消息..>");
            return true;
          }
        }else{
          $issuer->sendMessage("§c你没有权限使用此命令！");
          return true;
        }
      break;
      
      // 随机传送
      case "randomtp":
      case "随机传送":
        if($issuer->hasPermission("adminfun.command") || $issuer->hasPermission("adminfun.command.randomtp")){
          if($issuer instanceof Player){
            if(isset($args[1])){
              switch($args[1]){
                case "troll":
                case "整蛊":
                  if(isset($args[2])){
                    $target = $this->getPlugin()->getServer()->getPlayer($args[2]);
                    if($target !== null){
                      $target->setGamemode(0);
                      $target->teleport(new Position(rand(0, 255), rand(0, 255), rand(0, 255), $target->getLevel()));
                      $issuer->sendMessage("§a你将 ".$target->getName()." 传送到随机位置！");
                      $target->sendMessage("§l§e哈哈哈");
                      return true;
                    }else{
                      $issuer->sendMessage("§c无效的目标！");
                      return true;
                    }
                  }else{
                    $issuer->sendMessage("§c如果选择「整蛊」，必须输入目标名称！你总不会整蛊自己吧？");
                    return true;
                  }
                break;
                case "safe":
                case "安全":
                  if(isset($args[2])){
                    $target = $this->getPlugin()->getServer()->getPlayer($args[2]);
                    if($target !== null){
                      $x = rand(0, 255);
                      $z = rand(0, 255);
                      $y = rand(0, 200);
                      $target->teleport($target->getLevel()->getSafeSpawn(new Vector3($x, $y, $z)));
                      $issuer->sendMessage("§a已将 ".$target->getName()." 传送到安全随机位置");
                      return true;
                    }else{
                      $issuer->sendMessage("§c无效的目标！");
                      return true;
                    }
                  }else{
                    $x = rand(0, 255);
                    $z = rand(0, 255);
                    $y = rand(0, 200);
                    $issuer->teleport($issuer->getLevel()->getSafeSpawn(new Vector3($x, $y, $z)));
                    $issuer->sendMessage("§a已传送到安全随机位置");
                    return true;
                  }
                break;
                default:
                  $issuer->sendMessage("§c无效的参数！请使用「整蛊」或「安全」");
                  return true;
              }
            }else{
              $issuer->sendMessage("§c使用方法: /逗人 随机传送 <整蛊|安全> <玩家>");
              return true;
            }
          }else{
            $issuer->sendMessage("§c此命令只能在游戏内使用！");
            return true;
          }
        }else{
          $issuer->sendMessage("§c你没有权限使用此命令！");
          return true;
        }
      break;
      
      // 重载
      case "reload":
      case "重载":
        if($issuer->hasPermission("adminfun.command") || $issuer->hasPermission("adminfun.command.reload")){
          $this->getPlugin()->saveDefaultConfig();
          $this->getPlugin()->reloadConfig();
          $issuer->sendMessage("§bAdminFun §a已重新加载！");
          return true;
        }else{
          $issuer->sendMessage("§c你没有权限使用此命令！");
          return true;
        }
      break;
      
      // 火箭
      case "rocket":
      case "火箭":
        if($issuer->hasPermission("adminfun.command") || $issuer->hasPermission("adminfun.command.rocket")){
          if(isset($args[1])){
            $target = $this->getPlugin()->getServer()->getPlayer($args[1]);
            if($target !== null){
              $target->getLevel()->addSound(new LaunchSound($target));
              $motion = new Vector3($target->motionX, $target->motionY, $target->motionZ);
              $motion->y = 20;
              $target->setMotion($motion);
              $target->sendMessage("§b§l你变成了火箭！");
              $issuer->sendMessage("§a你将 ".$target->getName()." 变成了火箭！");
              return true;
            }else{
              $issuer->sendMessage("§c无效的目标！");
              return true;
            }
          }else{
            $issuer->sendMessage("§c使用方法: /逗人 火箭 <玩家>");
            return true;
          }
        }else{
          $issuer->sendMessage("§c你没有权限使用此命令！");
          return true;
        }
      break;
      
      // 刷屏广播
      case "spamcast":
      case "刷屏广播":
        if($issuer->hasPermission("adminfun.command") || $issuer->hasPermission("adminfun.command.spamcast")){
          if(count($args) > 1){
            unset($args[0]);
            $msg = implode(" ", $args);
            foreach($this->getPlugin()->getServer()->getOnlinePlayers() as $p){
              for($i=0;$i<20;$i++){
                $p->sendMessage("§l[刷屏广播] §3".$msg);
              }
            }
            $issuer->sendMessage("§a消息已刷屏发送！");
            return true;
          }else{
            $issuer->sendMessage("§c使用方法: /逗人 刷屏广播 <消息..>");
            return true;
          }
        }else{
          $issuer->sendMessage("§c你没有权限使用此命令！");
          return true;
        }
      break;
      
      // 交换
      case "swap":
      case "交换":
        if($issuer->hasPermission("adminfun.command") || $issuer->hasPermission("adminfun.command.swap")){
          if(isset($args[1]) && isset($args[2])){
            $player1 = $this->getPlugin()->getServer()->getPlayer($args[1]);
            $player2 = $this->getPlugin()->getServer()->getPlayer($args[2]);
            if($player1 !== null && $player2 !== null){
              if($player1->getName() != $player2->getName()){
                $x1 = $player1->x;
                $y1 = $player1->y;
                $z1 = $player1->z;
                $w1 = $player1->getLevel();
                $x2 = $player2->x;
                $y2 = $player2->y;
                $z2 = $player2->z;
                $w2 = $player2->getLevel();
                $player2->teleport(new Position($x1, $y1, $z1, $w1));
                $player1->teleport(new Position($x2, $y2, $z2, $w2));
                $issuer->sendMessage("§a已交换 ".$player1->getName()." 和 ".$player2->getName()." 的位置！");
                return true;
              }else{
                $issuer->sendMessage("§c不能交换同一个玩家！");
                return true;
              }
            }else{
              $issuer->sendMessage("§c无效的目标！");
              return true;
            }
          }else{
            $issuer->sendMessage("§c使用方法: /逗人 交换 <玩家1> <玩家2>");
            return true;
          }
        }else{
          $issuer->sendMessage("§c你没有权限使用此命令！");
          return true;
        }
      break;
      
      // 虚空
      case "void":
      case "虚空":
        if($issuer->hasPermission("adminfun.command") || $issuer->hasPermission("adminfun.command.void")){
          if(isset($args[1])){
            $target = $this->getPlugin()->getServer()->getPlayer($args[1]);
            if($target !== null){
              $target->teleport(new Position($target->x, 0, $target->z, $target->getLevel()));
              $target->sendMessage("§l§a欢迎来到虚空！");
              $issuer->sendMessage("§a你将 ".$target->getName()." 传送到了虚空！");
              return true;
            }else{
              $issuer->sendMessage("§c无效的目标！");
              return true;
            }
          }else{
            $issuer->sendMessage("§c使用方法: /逗人 虚空 <玩家>");
            return true;
          }
        }else{
          $issuer->sendMessage("§c你没有权限使用此命令！");
          return true;
        }
      break;
      
      // 未知子命令
      default:
        $issuer->sendMessage("§c未知的子命令！使用 /逗人 帮助 查看可用命令");
        return true;
    }
    return true;
  }
}
?>
