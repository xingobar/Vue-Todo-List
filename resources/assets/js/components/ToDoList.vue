<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-4">
                <h1>Todo List Vue js Example</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-4">
                <input type="text" v-model="newTodoText" @keyup.enter="add"
                       placeholder="加入一個新工作"/>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-4">
                <ul class="todo-list" >
                    <todo-list-item v-for="(todo,key,index) in list" :todo="todo"
                                    :key="todo.id"
                                :index="key" :filter="filter" class="todo-item"
                                v-on:remove="del(key)">
                    </todo-list-item>
                </ul>
            </div>
        </div>
        <div class="row" v-if="incompleteCount === 0 && filter === 'show_all'">
            <div class="col-md-8 col-md-offset-4">
                <div class="alert alert-success">
                    恭喜你完成所有的項目
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 col-md-offset-4">
                <a v-on:click="setFilter('show_all')" class="btn" :class="{active:filter === 'show_all'}">全部 ( {{allCount}} ) </a>
                <a v-on:click="setFilter('show_completed')" class="btn" :class="{active:filter === 'show_completed'}">已完成 ( {{completedCount}} )</a>
                <a v-on:click="setFilter('show_incomplete')" class="btn" :class="{active:filter === 'show_incomplete'}">未完成 ( {{incompleteCount}} )</a>
            </div>
        </div>

    </div>
</template>

<script>
    import TodoListItem from './ToDoListItem.vue';

    export default{
        name:'TodoList',
        components:{
            'todo-list-item':TodoListItem
        },
        props:['lists'],
        data() {
            return {
                todos:[],
                newTodoText:'',
                filter:'show_all'
            }
        },

        mounted(){
            this.todos = this.lists;
            console.log(this.todos);
        },

        methods:{
            add:function(){
                //var id = this._uuid();
                var id = this.todos[this.todos.length - 1].id;

                Vue.set(this.todos,this.todos.length,{
                    id:id + 1,
                    text: this.newTodoText,
                    isCompleted:false,
                    isEdit:false
                });

                axios.post('/create',{'text':this.newTodoText})
                    .then((response) => {
                        this.newTodoText = '';
                        console.log(response.data);
                    })
                    .catch(function(error){
                       console.log(error);
                    });
            },
            del:function(index){

                axios.post('/delete',{'id':this.todos[index].id})
                    .then((response) => {
                        console.log(response.data);
                    })
                    .catch(function(error){
                        console.log(error);
                    });

                Vue.delete(this.todos,index);
            },
            _uuid:function(){
                var d = Date.now();
                if(typeof performance !== 'undefined' && typeof performance.now() === 'function')
                {
                    d += performance.now();
                }
                return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
                    var r = (d + Math.random() * 16) % 16 | 0;
                    d = Math.floor(d / 16);
                    return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
                });
            },
            _getTodos:function(isCompleted){
                var list = [];

                for(var index in this.todos){
                    if(this.todos[index].isCompleted === isCompleted){
                      //  list[index] = this.todos[index];
                        list.push(this.todos[index]);
                    }
                }
                return list;
            },
            setFilter:function(filter){
                this.filter = filter;
            }
        },
        computed:{
            list:function(){
                if(this.filter === 'show_all'){
                    return this.todos;
                }else if(this.filter ==='show_completed'){
                    return this._getTodos(1);
                }else{
                    return this._getTodos(0);
                }
                return this.todos;
            },
            incompleteCount:function(){
                var _this = this;

                return Object.keys(this.todos).filter(function(value){
                   return !_this.todos[value].isCompleted;
                }).length;
            },
            allCount:function(){
                return Object.keys(this.todos).length;
            },
            completedCount:function(){
                var _this = this;
                return Object.keys(this.todos).filter(function(value){
                   return _this.todos[value].isCompleted
                }).length;
            }
        }
    }
</script>