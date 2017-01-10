<?php 

namespace App\Tasks;

use App\Student;
use App\Task;
use Carbon\Carbon;
use App\LessonBooking;

trait StudentTasksTrait
{

	/** Trait to add tasks to a student. Basic hierarchy: Not Replied > Mismatched no job > Mismatched has job
	 *  Create task if none of the higher hierarchy exists
	 */

	/**
	 * Update the student's tasks when status not replied
	 * @param Student $student
	 */
	public function updateTasksForNotReplied()
	{
		$this->deleteMismatchedNoJobTasks();
		$this->deleteMismatchedHasJobTasks();
		$this->createNotRepliedTask();
	}

	/**
	 * Update the student's tasks when status mismatched has job
	 * @param Student $student
	 */
	public function updateTasksForMismatchedHasJob()
	{
		if ($this->hasTaskCategory(Task::NOT_REPLIED)) return;
		$this->deleteMismatchedNoJobTasks();
		$this->createMismatchedHasJobTask();
	}

	/**
	 * Update the student's tasks when status mismatched has job
	 * @param Student $student
	 */
	public function updateTasksForMismatchedNoJob()
	{
		if ($this->hasTaskCategory(Task::NOT_REPLIED)) return;
		$this->deleteMismatchedHasJobTasks();
		$this->createMismatchedNoJobTask();
	}

	public function updateTasksForLessonConfirmed()
	{
		$this->deleteMismatchedHasJobTasks();
		$this->deleteMismatchedNoJobTasks();
		$this->deleteNotRepliedTasks();
	}


	protected function createNotRepliedTask()
	{
		if ($this->hasTaskCategory(Task::NOT_REPLIED)) return;

		$task = new Task();
		$task->category = Task::NOT_REPLIED;
		$task->action_at = Carbon::now();
		$task->body = 'NOT REPLIED';
		$this->tasks()->save($task);
	}

	protected function createMismatchedNoJobTask()
	{
		if ($this->hasTaskCategory(Task::MISMATCHED_NO_JOB)) return;

		$task = new Task();
		$task->category = Task::MISMATCHED_NO_JOB;
		$task->action_at = Carbon::now();
		$task->body = 'MISMATCHED NO JOB';
		$this->tasks()->save($task);
	}

	protected function createMismatchedHasJobTask()
	{
		if ($this->hasTaskCategory(Task::MISMATCHED_HAS_JOB)) return;

		$task = new Task();
		$task->category = Task::MISMATCHED_HAS_JOB;
		$task->action_at = Carbon::now();
		$task->body = 'MISMATCHED HAS JOB';
		$this->tasks()->save($task);
	}

	protected function deleteMismatchedHasJobTasks()
	{
		$tasks = $this->tasks()->where('category', '=', Task::MISMATCHED_HAS_JOB)->get();
		foreach ($tasks as $task) $task->delete();
	}

	protected function deleteMismatchedNoJobTasks()
	{
		$tasks = $this->tasks()->where('category', '=', Task::MISMATCHED_NO_JOB)->get();
		foreach ($tasks as $task) $task->delete();
	}

	public function deleteNotRepliedTasks()
	{
		$tasks = $this->tasks()->where('category', '=', Task::NOT_REPLIED)->get();
		foreach ($tasks as $task) $task->delete();
	}

	protected function hasTaskCategory($category)
	{
		$tasks = $this->tasks()->where('category', '=', $category)->count();
		if ($tasks) return true;
		return false;
	}

}