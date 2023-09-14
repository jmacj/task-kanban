<template>
    <VContainer>
        <VBtn
            fab
            color="primary"
            class="custom-fab"
            @click="openDialog(null)"
        >
        <VIcon>mdi-plus</VIcon>
        </VBtn>
        <VDialog v-model="dialog" max-width="500">
            <VCard>
                <VCardTitle>
                    <span class="headline">{{ formTitle }}</span>
                </VCardTitle>
                <VDivider/>
                <VCardText>
                    <VForm ref="form">
                        <VTextField
                            v-model="editedTask.title"
                            label="Title"
                            :rules="titleRules"
                        />
                        <VTextarea
                            v-model="editedTask.description"
                            label="Description"
                            :rules="descriptionRules"
                        />
                        <VMenu
                            v-model="menu"
                            :close-on-content-click="false"
                            :nudge-right="40"
                            transition="scale-transition"
                            min-width="auto"
                            offset-y
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <VTextField
                                    v-model="editedTask.due_date"
                                    label="Due Date"
                                    prepend-icon="mdi-calendar"
                                    :rules="dueDateRules"
                                    readonly
                                    v-bind="attrs"
                                    v-on="on"
                                    required
                                />
                            </template>
                            <VDatePicker
                                v-model="editedTask.due_date"
                                :min="today"
                                @input="menu = false"
                            />
                        </VMenu>
                    </VForm>
                </VCardText>
                <VCardActions>
                    <VSpacer/>
                    <VBtn color="primary" plain @click="save">Save</VBtn>
                    <VBtn color="error" plain @click="close">Cancel</VBtn>
                </VCardActions>
            </VCard>
        </VDialog>
        <VDialog v-model="deleteDialog" max-width="500px">
            <VCard>
                <VCardTitle class="text-h5">Are you sure you want to delete this task?</VCardTitle>
                <VCardActions>
                    <VSpacer/>
                    <VBtn color="primary" plain @click="deleteTask">OK</VBtn>
                    <VBtn color="error" plain @click="closeDeleteDialog">Cancel</VBtn>
                    <VSpacer/>
                </VCardActions>
            </VCard>
        </VDialog>
        <VRow>
            <KanbanColumn
                v-for="(list, index) in lists"
                :key="index"
                :list="list"
                :index="index"
                :onDragEnd="onDragEnd"
                :openDialog="openDialog"
                :openDeleteDialog="openDeleteDialog"
            />
        </VRow>

        <VOverlay :value="loading">
            <VProgressCircular indeterminate color="primary"/>
        </VOverlay>
    </VContainer>
</template>

<script>
import apiService from '../../services/apiService.js';
import KanbanColumn from '../components/KanbanColumn.vue';


export default {
    components: {
        KanbanColumn,
    },
    data() {
        return {
            lists: [
                {
                    title: 'To Do',
                    icon: 'mdi-progress-alert',
                    color: 'gray',
                    key: 'TODO',
                    items: [],
                },
                {
                    title: 'In Progress',
                    key: 'IN-PROGRESS',
                    icon: 'mdi-progress-clock',
                    color: 'primary',
                    items: [],
                },
                {
                    title: 'Completed',
                    key: 'COMPLETED',
                    icon: 'mdi-check-circle-outline',
                    color: 'success',
                    items: [],
                },
            ],
            status: [
                'TODO',
                'IN-PROGRESS',
                'COMPLETED',
            ],
            dialog: false,
            deleteDialog: false,
            editedTaskIndex: null,
            editedTask: {
                title: '',
                description: '',
                status: '',
                position: 0,
                created_at: '',
                updated_at: '',
            },
            defaultTask: {
                title: '',
                description: '',
                status: '',
                position: 0,
                created_at: '',
                updated_at: '',
            },
            menu: false,
            today: new Date().toISOString(),
            loading: false,
        };
    },
    computed: {
        formTitle() {
            return this.editedTaskIndex !== null ? 'Edit Task' : 'Create New Task'
        },
        titleRules() {
            return [v => v === null || v === undefined || !!v.trim() || 'Title is required.'];
        },
        descriptionRules() {
            return [v => v === null || v === undefined || !!v.trim() || 'Description is required.'];
        },
        dueDateRules() {
            return [v => !!v || 'Due Date is required.'];
        },
    },
    watch: {
        dialog (val) {
            val || this.close();
        },
        deleteDialog (val) {
            val || this.closeDeleteDialog();
        },
    },
    created() {
        this.initialize();
    },
    methods: {
        /**
         * initializes the board
         */
        initialize() {
            this.loading = true
            apiService.getTasks()
                .then( response => {
                    if (response.status === 200) {
                        let tasks = response.data;
                        this.lists[0].items = tasks['TODO'] || [];
                        this.lists[1].items = tasks['IN-PROGRESS'] || [];
                        this.lists[2].items = tasks['COMPLETED'] || [];
                    }
                })
                .catch(error => {
                    alert(error.response.data.message);
                    if (error.response.data.message === 'Unauthenticated.') {
                        window.location.href = '/login';
                    }
                })
                .finally(() => {
                    this.loading = false;
                });
        },

        /**
         * saving of tasks for create/edit
         */
        save() {
            this.loading = true;
            if (this.isRequired()) {
                if (this.editedTaskIndex !== null) {
                    this.editTaskRequest();
                } else {
                    this.editedTask.position = this.lists[0].items.length;
                    apiService.createTask({...this.editedTask })
                        .then(response => {
                            if (response.status === 200) {
                                this.initialize();
                            }
                        })
                        .catch(error => {
                            alert(error.response.data.message);
                            if (error.response.data.message === 'Unauthenticated.') {
                                window.location.href = '/login';
                            }
                        });
                }
                this.close();
            } else {
                alert('Please enter required fields');
            }
        },

        /**
         * checks for required fields
         * @returns {boolean}
         */
        isRequired() {
            return !!this.editedTask.title && !!this.editedTask.description && !!this.editedTask.due_date;
        },

        /**
         * handles edit request
         */
        editTaskRequest() {
            this.loading = true;
            apiService.updateTask(this.editedTask.id, { ...this.editedTask })
                .then(response => {
                    if (response.status === 200) {
                        this.initialize();
                    }
                })
                .catch(error => {
                    alert(error.response.data.message);
                    if (error.response.data.message === 'Unauthenticated.') {
                        window.location.href = '/login';
                    }
                });
        },

        /**
         * handles update of status and position
         * @param event
         */
        onDragEnd(event) {
            let oldStatus = event.from.parentNode.children[0].id;
            let newStatus = event.to.parentNode.children[0].id;
            let oldTaskIndex = event.oldIndex;
            let newTaskIndex = event.newIndex;
            if (oldStatus !== newStatus || oldTaskIndex !== newTaskIndex) {
                this.editedTask = this.lists.find(list => list.key === newStatus).items[newTaskIndex];
                this.editedTask.status = newStatus;
                this.editedTask.position = newTaskIndex;
                this.editedTaskIndex = newTaskIndex;

                this.editTaskRequest();
                this.editedTaskIndex = null;
                this.editedTask = this.defaultTask;
            }
        },

        /**
         * handles deleting of tasks
         */
        deleteTask() {
            apiService.deleteTask(this.editedTask.id)
                .then(response => {
                    if (response.status === 200) {
                        this.initialize();
                    }
                })
                .catch(error => {
                    alert(error.response.data.message);
                    if (error.response.data.message === 'Unauthenticated.') {
                        window.location.href = '/login';
                    }
                });
            this.closeDeleteDialog();
        },

        /**
         * opens create/edit modal
         * @param index
         * @param editedTaskIndex
         */
        openDialog(index, editedTaskIndex = null) {
            if (this.$refs.form !== undefined) {
                this.$refs.form.reset();
            }
            this.editedTaskIndex = editedTaskIndex;
            this.dialog = true;
            if (editedTaskIndex !== null) {
                this.editedTask = this.lists[index].items[editedTaskIndex];
            }
        },

        /**
         * closes create/edit modal and resets data
         */
        close() {
            this.dialog = false;
            this.$nextTick(() => {
                this.editedTask = Object.assign({}, this.defaultTask);
                this.editedTaskIndex = null;
            });
        },

        /**
         * opens delete confirmation
         * @param index
         * @param editedTaskIndex
         */
        openDeleteDialog(index, editedTaskIndex) {
            this.editedTaskIndex = editedTaskIndex;
            this.editedTask = this.lists[index].items[editedTaskIndex];
            this.deleteDialog = true;
        },

        /**
         * closes delete confirmation and resets data
         */
        closeDeleteDialog() {
            this.deleteDialog = false;
            this.$nextTick(() => {
                this.editedTask = Object.assign({}, this.defaultTask);
                this.editedTaskIndex = null;
            });
        },
    },
};
</script>

<style>
.custom-fab {
    position: fixed;
    bottom: 16px;
    right: 16px;
}
</style>
