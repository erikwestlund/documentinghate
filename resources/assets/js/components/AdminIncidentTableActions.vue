<template>
    <div>
        <a class="action-link" :href="'/admin/incidents/' + rowData.id + '/moderate'"><i class="fa fa-check-square-o"></i> Moderate</a>
        <a class="action-link" :href="'/admin/incidents/' + rowData.id"><i class="fa fa-edit"></i> Edit</a>
    </div>
</template>

<script>
    export default {
        props: { 
            rowData: {
                type: Object,
                required: true
            }
        },
        methods: {
            itemAction: function(action, data) {
                console.log(data.city);
            },
            onClick: function(event) {
                console.log('custom-action: on-click', event.target)
            },
            onDoubleClick: function(event) {
                console.log('custom-action: on-dblclick', event.target)
            }
        },
        data() {
            return {

            }
        },    
    }

</script>