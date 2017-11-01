<template>

    <li>
        <input type="checkbox" v-on:change="updateStatus(todo)"
               :checked="todo.isCompleted"/>
        <label v-if="!todo.isEdit" v-bind:class="[todo.isCompleted ? 'complete' :'']">
            {{todo.text}}
        </label>
        <input type="text" v-if="todo.isEdit" v-on:keyup.enter="updateTodo($event,todo)"
               v-model="todo.text"/>
        <a v-on:click="editTodo(todo)" v-if="!todo.isEdit" class="btn">編輯</a>
        <a v-on:click="remove(index)" class="btn">刪除</a>
    </li>

</template>

<script>
    export default{
        name:'TodoListItem',
        props: ['todo','index','filter'], // 接收父元素所傳來的資料
        methods:{
            remove:function(index){
                this.$emit('remove');
            },
            updateTodo:function($event,todo){
                if($event.target.value)
                {
                    todo.text = $event.target.value;
                    axios.post('/edit',{'id':todo.id,'text':$event.target.value})
                        .then((response) => {
                            console.log(response.data);
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                }
                todo.isEdit = !todo.isEdit;
            },
            updateStatus:function(todo){
                todo.isCompleted = !todo.isCompleted;

                axios.post('/updateStatus',{'id':todo.id,'isCompleted':todo.isCompleted})
                    .then((response ) => {
                        console.log(response.data);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            editTodo:function(todo){
                todo.isEdit = !todo.isEdit;
            }
        }
    }
</script>