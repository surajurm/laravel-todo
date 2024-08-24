<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
       

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
        <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
          

            <div class="container shadow py-4 my-4" id="vue-instance">
                <div class="py-4">
                    <div class="row">
                        <div class="col">
                           <h4 class="text-gray-900 dark:text-white text-center">PHP - Simple To Do List APP &nbsp;&nbsp;<button class="btn btn-primary btn-md" @click="showAll">Show All Task</button></h4>
                           
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                           <form class="row g-3 py-4 justify-center" style="justify-content:center">
                              <div class="col-auto">
                                <label for="task" class="visually-hidden">Task</label>
                                <input type="text" class="form-control" id="task" v-model="taskInput" required placeholder="Please enter task name">
                              </div>
                              <div class="col-auto">
                                <button type="submit" class="btn btn-primary mb-3" @click="addTask">Add Task</button>
                              </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col"> 
                            <table class="table py-4" width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Task</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="task in tasks">
                                        <td>@{{ task.id}}</td>
                                        <td>@{{ task.task}}</td>
                                        <td>@{{ task.status}}</td>
                                        <td>
                                            <button v-if="task.status != 'Done'" class="btn btn-success" @click="updateTaskStatus(task.id)">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2-square" viewBox="0 0 16 16">
                                                  <path d="M3 14.5A1.5 1.5 0 0 1 1.5 13V3A1.5 1.5 0 0 1 3 1.5h8a.5.5 0 0 1 0 1H3a.5.5 0 0 0-.5.5v10a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V8a.5.5 0 0 1 1 0v5a1.5 1.5 0 0 1-1.5 1.5z"/>
                                                  <path d="m8.354 10.354 7-7a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0"/>
                                                </svg>
                                            </button>&nbsp;&nbsp;
                                            <button class="btn btn-success" @click="deleteTask(task.id)"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                              <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                                            </svg></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script type="text/javascript" >
            
            const app = Vue.createApp({
                data() {
                    return {
                      tasks: [], 
                      taskInput: ''
                    }
                }, 
                methods:{
                    async fetchTaskList(){
                        const taskResponse  = await fetch("/tasks");
                        const taskJson = await taskResponse.json();
                        this.tasks = taskJson.task;
                    },
                    showAll(){
                        this.fetchTaskList();
                    },
                    addTask(event){
                        event.preventDefault();
                        if(this.taskInput != ''){
                            $.ajax({
                                url: "/task", 
                                method: "POST",
                                headers: {
                                    'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
                                }, 
                                data: {'task': this.taskInput, "_token": this.token}, 
                                success: function(response){
                                    alert("Task Saved"); 
                                }, 
                                error: function(response){
                                    const errorMessage  = JSON.parse(response.responseText);
                                    alert(errorMessage.message);
                                }
                            })
                            this.fetchTaskList()
                        }else{
                            alert("Please enter task");
                        }
                    }, 
                    deleteTask(taskId){
                        if(confirm("Are u sure to delete this task ?")){
                            $.ajax({
                                url: "/task/"+taskId, 
                                method: "DELETE",
                                headers: {
                                    'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
                                },
                                success: function(response){

                                    alert("Task Deleted!");
                                }
                            })

                            this.fetchTaskList()
                        }
                    },
                    updateTaskStatus( taskId){
                        $.ajax({
                            url: "/task/"+taskId, 
                            method: "PUT",
                            headers: {
                                'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
                            }, 
                            data: {'task_id': taskId, "status": "Done"}, 
                            success: function(response){
                                alert("Task Updated!");
                            }
                        })
                        this.fetchTaskList()
                    }
                }, 
                mounted(){  
                    this.fetchTaskList()
                }
               
            });
            
            app.mount('#vue-instance')

        </script>
    </body>
   
</html>
