<?php


require_once ('aws-2.8.30.phar');



if(isset($argv) &&  count($argv) > 2 ){


	$options = array(			
			'profile' => 'default',
			'region'  => 'us-east-1',
			'description'  => 'Backup',
			'max-backups' => 1,
			'server' => ''
	);
   
    for($i=1;$i < count(  $argv ); ++$i ){
   		$o = explode("=", $argv[$i] );
   		$options[ $o[0]  ] = $o[1];
    }
   
   
	
	echo "Volume-Id: ".$options['volume-id']."\n";
	echo "Max No Snapshots: ".$options['max-backups']."\n";
	
	$objDateTime = new DateTime('NOW');
	
	echo $objDateTime->format('c')."\n";
	
	$ec2Client = \Aws\Ec2\Ec2Client::factory(array( 'profile' => 'default', 'region'  => $options['region'] , 'version'=>'2015-10-01' ));
	
	
	$s = $ec2Client->describeSnapshots(
			array(
					'Filters' => array(
							array(
									'Name' => 'volume-id',
									'Values' => array($options['volume-id'])
							),
					),
			)
			);
	
	$v = $ec2Client->describeVolumes(array(	'VolumeIds' => array($options['volume-id']) ));
	
	//print_r(  $v  );
	
	//exit;
	
	$snapshots = array();
	
	if(  count( $s['Snapshots']) >= $options['max-backups']  ){
		foreach (  $s['Snapshots'] as $snapshot ){
			$snapshots[ strtotime( $snapshot['StartTime'] )  ] = $snapshot;
		}
		ksort($snapshots);
		$snapshotId = array_values($snapshots)[0]['SnapshotId'];
		$ec2Client->deleteSnapshot(array(  'DryRun' => false,  'SnapshotId' => $snapshotId  ));
	
	}
	
	$result = $ec2Client->createSnapshot(array( 'VolumeId' =>  $options['volume-id'], 'Description' => $options['description']   ));
	
	
	$ec2Client->createTags(  array(
			'DryRun' => false,
			'Resources' => array(  $result['SnapshotId']  ),
			'Tags' => array(
					array( 'Key' => 'Attachment', 'Value' =>  $v['Volumes'][0]['Attachments'][0]['Device'] ),
					array( 'Key' => 'Server',     'Value' =>  $options['server']  ),
					array( 'Key' => 'InstanceId', 'Value' =>  $v['Volumes'][0]['Attachments'][0]['InstanceId']   )
			)
	));
	
	echo "Creating snapshot ".$result['SnapshotId']."....... \n";
	$snapshots = $ec2Client->describeSnapshots(array(
			'SnapshotIds' => array($result['SnapshotId'])
	))->get('Snapshots');
	
	
	echo('Waiting to complete the snapshot'."\n");
	//print_r($snapshots);
	$ec2Client->waitUntilSnapshotCompleted($snapshots);
	echo('Snapshot completed at ');
	$objDateTime = new DateTime('NOW');
	echo $objDateTime->format('c')."\n";
		
    
	

}else{
	
	
	echo "\n 3 parameters are required, volum-id and quantity\n\n";
	
	
}


