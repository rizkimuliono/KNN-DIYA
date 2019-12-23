<?php

$dtTestNN = array();
$dtTrainingNN = array();
$jumK = 4;
$jumNNY = 0;
$jumNNN = 0;
$dtTestNN = array(1,1,1,1,2,1);
$dtTrainingNN = array(
  array(1,1,1,1,1,1,1),
  array(1,1,2,1,1,1,1),
  array(2,2,2,1,1,2,2),
  array(1,1,2,1,2,2,1)
);

$rnum = count($dtTrainingNN);
for($i=0;$i<$rnum;$i++)
{
  $dtTemp[$i]=sqrt((pow($dtTestNN[0]-$dtTrainingNN[$i][0],2))
  +(pow($dtTestNN[1]-$dtTrainingNN[$i][1],2))+(pow($dtTestNN[2]-$dtTrainingNN[$i][2],2))
  +(pow($dtTestNN[3]-$dtTrainingNN[$i][3],2))+(pow($dtTestNN[4]-$dtTrainingNN[$i][4],2))
  +(pow($dtTestNN[5]-$dtTrainingNN[$i][5],2)));
  $dtNN[$i]= $dtTrainingNN[$i][6]+$dtTemp[$i];
}
print_r($dtNN);

//sort program
for($i=1;$i<$rnum;$i++)
{
  for($j=$rnum-1;$j>0;$j--)
  {
    if($dtNN[$j-1]>$dtNN[$j])
    {
      $temp = $dtNN[$j];
      $dtNN[$j] = $dtNN[$j-1];
      $dtNN[$j-1] = $temp;

      $temp2 = $dtTemp[$j];
      $dtTemp[$j] = $dtTemp[$j-1];
      $dtTemp[$j-1] = $temp2;
    }
  }
}
//1-NN
if(($dtNN[0]-$dtTemp[0])==1)
{
  $kesimpulanNN[0]="yes";
}
else
{
  $kesimpulanNN[0]="no";
}
//sort based category k-NN

for($k=0;$k<$jumK;$k++)
{
  if(($dtNN[$k]-$dtTemp[$k])==1)
  {
    $jumNNY++;
  }
  else
  {
    $jumNNN++;
  }
}

if($jumNNY > $jumNNN)
{
  $kesimpulanNN[1] = "yes";
}
else if($jumNNN > $jumNNY)
{
  $kesimpulanNN[1] = "no";
}
else
{
  $kesimpulanNN[1] = "relatif";
}

print_r($kesimpulanNN);
?>
