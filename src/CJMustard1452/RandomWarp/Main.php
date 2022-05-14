<?php

declare(strict_types=1);

namespace CJMustard1452\RandomWarp;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\level\Level;
use pocketmine\level\Location;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{

	public function onEnable() :void{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		$Config = $this->myConfig = new Config($this->getDataFolder() . "CoordConfig.json", Config::JSON);
		if(isset($args[0])){
			//---------------SET---------------\\
			if(strtolower($args[0]) === "set"){
				if($sender->hasPermission("randomwarp.admin")){
						if($Config->get("isset") == null){
							if(isset($args[8])){
								if($this->getServer()->getLevelByName($args[7]) instanceof Level){
									$Config->set("isset", true);
									$Config->set("xpos1", intval($args[1]));
									$Config->set("ypos1", intval($args[2]));
									$Config->set("zpos1", intval($args[3]));
									$Config->set("xpos2", intval($args[4]));
									$Config->set("ypos2", intval($args[5]));
									$Config->set("zpos2", intval($args[6]));
									$Config->set("level_name", $args[7]);
									$Config->set("tp_password", strtolower($args[8]));
									$Config->save();
									$sender->sendMessage("§aRandom Warp information has been successfully saved.");
								}else{
									$sender->sendMessage("§c" . $args[7] . " is not a valid world.");
								}
						}else{
								$sender->sendMessage("§cInvalid Format, §b(Pos1 Coords) (Pos2 Coords) (World Name) (TP Pass)");
						}
					}else{
							$sender->sendMessage("§cdetails have already been filled out use /randomwarp delete to replace them.");
					}
				}else{
					$sender->sendMessage("§cYou don't have permission to run this command.");
				}
					//---------------DELETE---------------\\
				}elseif(strtolower($args[0]) === "delete"){
				if($sender->hasPermission("randomwarp.admin")){
					if("isset" == true){
						$Config->set("isset", null);
						$Config->set("xpos1", false);
						$Config->set("ypos1", false);
						$Config->set("zpos1", false);
						$Config->set("xpos2", false);
						$Config->set("ypos2", false);
						$Config->set("zpos2", false);
						$Config->set("level_name", false);
						$Config->set("tp_password", false);
						$Config->save();
						$sender->sendMessage("§aRandom Warp information has been successfully deleted.");
					}else{
						$sender->sendMessage("§cThere is no information to delete.");
					}
				}else{
					$sender->sendMessage("§cYou don't have permission to run this command.");
				}
					//---------------TELEPORT---------------\\
				}elseif(strtolower($args[0]) === strtolower($Config->get("tp_password"))){
				if($Config->get("isset") == true){
					if($this->getServer()->getLevelByName($Config->get("level_name")) instanceof Level){
						$plr = $this->getServer()->getPlayer($sender->getName());
						$level = $this->getServer()->getLevelByName($Config->get("level_name"));
						$x = rand($Config->get("xpos1"), $Config->get("xpos2"));
						$y = rand($Config->get("ypos1"), $Config->get("ypos2"));
						$z = rand($Config->get("zpos1"), $Config->get("zpos2"));
						$pitch = -2.77270507812;
						$yaw = 93.488403320312;
						$plr->teleport(new Location($x, $y, $z, $yaw, $pitch, $level));
						$sender->sendMessage("§aYou have been teleported to a random location.");
					}else{
						$sender->sendMessage("§cError, something went wrong.");
					}
				}else{
					$sender->sendMessage("§cError, something went wrong.");
				}
				}else{
				$sender->sendMessage("§b/rw (set | delete | tpass)");
			}
			}else{
			$sender->sendMessage("§b/rw (set | delete | tpass)");
		}
		return true;
	}
	}
