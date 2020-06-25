<script>
    import BaseSync from '@/components/Base/BaseSync.vue'

    export default {
        name: "crud-actions",
        extends: BaseSync,
        beforeDestroy() {
            this.mixin.admin.notification.active = false;
        },
        methods: {
            isValid() {
                // this.$validator._paused = false;
                this.$bus.$emit('validate');
                return new Promise((resolve, reject) => {
                    this.$bus.$on('validated', (result) => {
                        if (result)
                            resolve(result)
                        reject(result)
                    });
                })
            },
            save(data, event = 'created') {
                this.isValid().then(() => {
                    this.send(this.repository.save(data), this.repository.getResource() + '/saving').then((result) => {
                        this.$bus.$emit(event, result);
                    })
                })
            },
            saveAndReload(data) {
                this.save(data, 'reloaded')
            },
            update(id, data) {
                this.isValid().then(() => {
                    this.send(this.repository.update(id, data), this.repository.getResource() + '/updating').then((result) => {
                        this.$bus.$emit('updated', result);
                    })
                })
            },
            destroy(id) {
                this.isValid().then(() => {
                    this.isValid().then(() => {
                        this.send(this.repository.delete(id), this.repository.getResource() + '/deleting').then((result) => {
                            this.$bus.$emit('deleted', result);
                        })
                    })
                })
            },
            send(promise, event) {
                this.mixin.sending_now = event;
                return promise
                    .then((response) => {
                        return this.parseResponse(response) ?
                            response.data.result :
                            reject('error');
                    })
                    .catch((response) => {
                        this.parseResponse(response);
                    })
                    .finally(() => {
                        this.mixin.sending_now = '';
                    })
            }
        }
    }
</script>