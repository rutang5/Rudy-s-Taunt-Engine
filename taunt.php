<?php
define("MAX_NUM_INSULTS",10);
define("TAUNT_FILENAME","tauntlib.txt");

$taunts = file(TAUNT_FILENAME); // holds the master taunt library
$used = array(); // keep track of the vicious burns to deliver

$input_index = explode(" ",$_POST["text"]);

if (strtolower($input_index[0])=="add")
	{
	for ($i = 1; $i < count($input_index); $i++) // grab the rest of the string which should be the taunt. Ignore the first block (command add).
		{
		$toAdd .= $input_index[$i]." ";
		}
	$file = TAUNT_FILENAME;
	$current = file_get_contents($file);
	$current .= "\n".$toAdd;
	file_put_contents($file,$current);
	$text = ">:point_up: *".$toAdd."* has been added to my taunt library. Thanks!"; 
	}
else if (count($input_index) > 1)
	{
	//print_r($input_index);
	$userName = $input_index[0];
	$numTauntsDesired = $input_index[1];
	if ((is_numeric($numTauntsDesired) && ($numTauntsDesired <= MAX_NUM_INSULTS)))
		{
		while(count($used) < $numTauntsDesired)
			{
			$taunt_index = array_rand($taunts);
			if(!in_array($taunt_index, $used))
				{
				$used[] = $taunt_index; // add current index to used array
				$taunt = $taunts[$taunt_index];
				$text .= ">:stuck_out_tongue_winking_eye: *".$userName."*"." ".$taunt;
				}
			}
		}
	}
else if (strtolower($input_index[0]) == "libsize")
	{
	$text = ">:point_up: *".count($taunts)."*"." total taunts in my library.";
	}
else 
	{
	$taunt_index = array_rand($taunts);
	$text = ">:stuck_out_tongue_winking_eye: *".$_POST["text"]."*"." ".$taunts[$taunt_index];
	}

// $used2 = print_r($used);

$message = array (
    'response_type' => 'in_channel',
   'text' => $text
    );

header ('content-type: application/json');
echo json_encode ($message);

?>