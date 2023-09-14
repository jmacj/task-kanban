<template>
    <VCol cols="12" md="4">
        <VCard class="kanban-column pa-2" elevation="2" outlined>
            <div :id="list.key" class="text-h5 text-center">
                <VChip class="ma-2" :color="list.color">
                    <VIcon left>{{ list.icon }}</VIcon>
                    <h3 class="text-h5">{{ list.title }}</h3>
                </VChip>
            </div>
            <VDivider/>
            <Vuedraggable v-model="list.items" group="kanban" @end="onDragEnd">
                <TaskCard
                    v-for="(item, itemIndex) in list.items"
                    :key="itemIndex"
                    :item="item"
                    :index="index"
                    :itemIndex="itemIndex"
                    :openDialog="openDialog"
                    :openDeleteDialog="openDeleteDialog"
                />
            </Vuedraggable>
        </VCard>
    </VCol>
</template>

<script>
import TaskCard from './TaskCard.vue';
import draggable from 'vuedraggable';

export default {
    components: {
        Vuedraggable: draggable,
        TaskCard,
    },
    props: {
        list: Object,
        index: Number,
        onDragEnd: Function,
        openDialog: Function,
        openDeleteDialog: Function,
    },
};
</script>

<style>
.kanban-column {
    min-height: 200px;
    justify-content: center; /* Center vertically */
    align-items: center; /* Center horizontally */
}
</style>
