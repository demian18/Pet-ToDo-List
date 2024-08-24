<?php

namespace Http\controllers;

use Carbon\Carbon;
use Core\Request;
use Core\Services\Stat;
use Core\Services\Task;
use Core\Services\User;
use Exception;

class ReportController
{
    private Stat $statService;
    private User $userService;
    private Task $taskService;
    private Request $request;

    public function __construct(Stat $statService, User $userService, Task $taskService, Request $request)
    {
        $this->statService = $statService;
        $this->userService = $userService;
        $this->taskService = $taskService;
        $this->request = $request;
    }

    public function getOverview()
    {
        try {
            $totalTasks = $this->taskService->getTotalTasks();
            $completedTasks = $this->statService->getCountByStatus(1);
            $canceledTasks = $this->statService->getCountByStatus(3);

            $data = [
                'total_tasks' => $totalTasks,
                'completed_tasks' => $completedTasks,
                'canceled_tasks' => $canceledTasks,
            ];

            $json = json_encode($data);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Error encoding JSON: ' . json_last_error_msg());
            }

            header('Content-Type: application/json');
            echo $json;

        } catch (Exception $e) {
            header('Content-Type: application/json', true, 500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getUserStats()
    {
        try {
            $userId = $this->request->query('id');
            if (!$userId || !is_numeric($userId)) {
                throw new Exception('Valid User ID is required');
            }

            $user = $this->userService->editUser($userId);
            if (!$user) {
                throw new Exception('User not found');
            }

            $userPeriod = Carbon::parse($user->period);
            $time = $userPeriod->diffForHumans();

            $completedTasks = $this->statService->getCountByUser($userId, 1);
            $canceledTasks = $this->statService->getCountByUser($userId, 3);

            $data = [
                'user_id' => $userId,
                'name' => $user->name,
                'completed_tasks' => $completedTasks,
                'canceled_tasks' => $canceledTasks,
                'time_in_the_company' => $time
            ];

            $json = json_encode($data);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Error encoding JSON: ' . json_last_error_msg());
            }

            header('Content-Type: application/json');
            echo $json;
        } catch (Exception $e) {

            header('Content-Type: application/json', true, 500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getPerformance()
    {
        try {
            $startDate = $this->request->query('start_date');
            $endDate = $this->request->query('end_date');

            if (!$startDate || !$endDate) {
                throw new Exception('Both start_date and end_date are required.');
            }

            try {
                $start = Carbon::createFromFormat('Y-m-d', $startDate);
                $end = Carbon::createFromFormat('Y-m-d', $endDate);
            } catch (\Exception $e) {
                throw new Exception('Invalid date format. Use YYYY-MM-DD.');
            }

            if ($start->greaterThan($end)) {
                throw new Exception('Start date cannot be after end date.');
            }

            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);

            $totalTasks = $this->taskService->getTotalTasksInPeriod($start, $end);
            $completedTasks = $this->statService->getCountByStatusInPeriod(1, $start, $end);
            $canceledTasks = $this->statService->getCountByStatusInPeriod(3, $start, $end);

            $data = [
                'total_tasks' => $totalTasks,
                'completed_tasks' => $completedTasks,
                'canceled_tasks' => $canceledTasks,
            ];

            $json = json_encode($data);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Error encoding JSON: ' . json_last_error_msg());
            }

            header('Content-Type: application/json');
            echo $json;
        } catch (Exception $e) {
            header('Content-Type: application/json', true, 500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}