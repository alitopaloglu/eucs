<?php

namespace App\Controller;

use App\Helpers\Helper;
use App\Repository\DevelopersRepository;
use App\Repository\ProvidersRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class PlanController
{
    const WeeklyHours = 45;

    /**
     * @var ProvidersRepository
     */
    private $providersRepository;
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;
    /**
     * @var DevelopersRepository
     */
    private $developersRepository;
    /**
     * @var Helper
     */
    private $helper;

    public function __construct(Helper $helper, ProvidersRepository $providersRepository,DevelopersRepository $developersRepository, ManagerRegistry  $managerRegistry)
    {
        $this->helper               = $helper;
        $this->providersRepository  = $providersRepository;
        $this->developersRepository = $developersRepository;
        $this->managerRegistry      = $managerRegistry;
    }
    /**
     * @Route("/plan", name="app_plan")
     * @return Response
     */
    public function plan()
    {
        $providers  = $this->providersRepository->findAll();
        $developers = $this->developersRepository->findAll();

        $totalJobSP = 0;
        $totalDeveloperSP = 0;

        $jobsArray = array();
        $developersArray = array();

        foreach ($providers as $provider)
        {
            $className = "App\\Repository\\" . $provider->getCode() . "Repository";
            $providerRepository = new $className($this->managerRegistry);
            $providerJobs = $providerRepository->findAll();
            foreach ($providerJobs as $providerJob)
            {
                $totalJobSP += $providerJob->getLevel() * $providerJob->getEstimatedDuration();
                $jobsArray[] = array(
                    'name'                  => $providerJob->getName(),
                    'level'                 => $providerJob->getLevel(),
                    'estimated_duration'    => $providerJob->getEStimatedDuration()
                );
            }
        }

        foreach ($developers as $developer)
        {
            $totalDeveloperSP += $developer->getEstimatedDuration() * $developer->getLevel();
            $developersArray[] = array(
                'id'                    => $developer->getId(),
                'name'                  => $developer->getName(),
                'level'                 => $developer->getLevel(),
                'estimated_duration'    => $developer->getEstimatedDuration());
        }

        $minimumHoursToGetAllJobsDone = $totalJobSP / $totalDeveloperSP;
        $minimumWeeksToGetAllJobsDone = $minimumHoursToGetAllJobsDone / self::WeeklyHours;

        echo 'Total Job SP:' . $totalJobSP;
        echo '<br>';
        echo 'Total Developer SP Capacity Per Hour:' . $totalDeveloperSP;
        echo '<br>';
        echo 'Minimum Possible Hours to Get All Jobs Done:' . $minimumHoursToGetAllJobsDone;
        echo '<br>';
        echo 'Minimum Possible Weeks to Get All Jobs Done:' . $minimumWeeksToGetAllJobsDone;
        echo '<br>';

        usort($jobsArray,"App\\Helpers\\Helper::compare");
        usort($developersArray,"App\\Helpers\\Helper::compare");

        $i = 0;
        foreach ($jobsArray as $item)
        {
            $i += $item['level'] * $item['estimated_duration'];
        }

        $return = '';
        for($i = 1; $i <= ceil($minimumWeeksToGetAllJobsDone); $i++)
        {
            $developerWeeklySPCapacity = array();
            $return .= '<br>Week '.$i.'<br><br>';
            foreach ($developersArray as $dev)
            {
                $return .= $dev['name'].':<br>';
                $developerWeeklySPCapacityStart = $developerWeeklySPCapacity[$dev['id']] = $dev['estimated_duration'] * $dev['level'] * self::WeeklyHours;
                $devJobs = array();

                foreach ($jobsArray as $key => &$job)
                {
                    $jobSP = $job['level'] * $job['estimated_duration'];
                    if(empty($job['is_assigned']) && $jobSP < $developerWeeklySPCapacity[$dev['id']])
                    {
                        $developerWeeklySPCapacity[$dev['id']] -= $jobSP;
                        $job['is_assigned'] = true;
                        $devJobs[] = $job;
                        if(($developerWeeklySPCapacityStart - $developerWeeklySPCapacity[$dev['id']]) > ($developerWeeklySPCapacityStart / count($developersArray)) && $i == ceil($minimumWeeksToGetAllJobsDone))
                        {
                            break;
                        }
                    }
                }

                $devWeeklyHours = 0;
                $devWeeklySP = 0;
                foreach ($devJobs as $devJob)
                {
                    $return .= 'Name:'.$devJob['name'].', Level:'.$devJob['level'].', Estimated Duration:'.$devJob['estimated_duration'].'<br>';
                    $devWeeklyHours += $devJob['estimated_duration'];
                    $devWeeklySP += $devJob['estimated_duration'] * $devJob['level'];
                }
                $return .= 'Total SP Used/Capacity:'. $devWeeklySP.'/'.$developerWeeklySPCapacityStart.'<br><br>';
            }
        }

        return new Response($return);
    }
}
