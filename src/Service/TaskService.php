<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Account;
use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Repository\CategoryRepository;
use App\Utils\Tools;

class TaskService
{
    private TaskRepository $taskRepository;
    private CategoryRepository $categoryRepository;

    
    public function __construct()
    {
        //Injection des dépendances
        $this->taskRepository = new TaskRepository();
        $this->categoryRepository = new CategoryRepository();
    }

    public function insertTask(array $task): string 
    {
        //1 Vérifier si les champs sont vides
        if (
            empty($task["title"]) ||
            empty($task["description"])
        ) {
            return "Veuillez remplir les champs obligatoires";
        }
        //2 Nettoyer les entrées utilisateurs
        Tools::sanitize_array($task);

        //3 Hydrater le tableau (Super globale POST)
        $addTask = $this->taskRepository->hydrateTask($task);
        
        //4 Ajout en BDD
        $this->taskRepository->addTask($addTask);

        return "La tache : " . $addTask->getTitle() . " a été ajouté en BDD";
    }
}
