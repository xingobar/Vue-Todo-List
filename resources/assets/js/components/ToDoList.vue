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
                                    :key="todo.uuid"
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
        data() {
            return {
                todos: {
                    "a5436691-350c-4ed0-862e-c8abc8509a4a": {
                        "uuid": "a5436691-350c-4ed0-862e-c8abc8509a4a",
                        "text": "買一本好書",
                        "isCompleted": false,
                        "isEdit": false
                    },
                    "a98bf666-a710-43b2-81b2-60c68ec4688d": {
                        "uuid": "a98bf666-a710-43b2-81b2-60c68ec4688d",
                        "text": "打電話給小明",
                        "isCompleted": true,
                        "isEdit": false
                    },
                    "452ef417-033d-48ff-9fec-9d686c105dce": {
                        "uuid": "452ef417-033d-48ff-9fec-9d686c105dce",
                        "text": "寫一篇文章",
                        "isCompleted": false,
                        "isEdit": false
                    }
                },
                newTodoText:'',
                filter:'show_all'
            }
        },

        methods:{
            add:function(){
                var id = this._uuid();

                Vue.set(this.todos,id,{
                    uuid:id,
                    text: this.newTodoText,
                    isCompleted:false,
                    isEdit:false
                });
                this.newTodoText = '';
            },
            del:function(index){
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
                var list = {};

                for(var index in this.todos){
                    if(this.todos[index].isCompleted === isCompleted){
                        list[index] = this.todos[index];
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
                    return this._getTodos(true);
                }else{
                    return this._getTodos(false);
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