export default class {
    constructor(container, {
        handle, child, selector, group, itemClass, floatingClass, sort = false,
        overClass, onDrop, onDragStart, onDragEnd, onDragOver, onDragging
    }) {
        this.uuid = Math.random().toString(36).substring(7);
        this.handle = handle;
        this.selector = selector;
        this.group = group;
        //Sort es el atributo que da permiso de manejo de DOM usando los dataTransfer y reemplazando html,
        //de lo contrario es con el uso de los dataset y que Vue interactue con el DOM
        this.sort = sort;
        this.itemClass = itemClass;
        this.floatingClass = floatingClass;
        this.overClass = overClass;
        this.onDragging = onDragging || function () {
        };
        this.onDragStart = onDragStart || function () {
        };
        this.onDragEnd = onDragEnd || function () {
        };
        this.onDragOver = onDragOver || function () {
        };
        this.onDragging = onDragging || function () {
        };
        this.onDrop = onDrop || function () {
        };
        this.container = child ? container.querySelector(child) : container;

        const draggables = this.selector ? this.container.querySelectorAll(this.selector) : this.container.children;

        this.el = null;
        this.over = null;
        this.from = null;
        this.to = null;

        this.init(draggables);
    }

    init(draggables) {
        this.mutationObserver().observe(this.container, {childList: true});

        if (this.handle) {
            Array.from(draggables).forEach(item => {
                const h = item.querySelector(this.handle);
                if(h){
                    h.addEventListener('mousedown', this.handleEvents(item).down, false);
                    h.addEventListener('mouseup', this.handleEvents(item).up, false);
                }
            })
        }
        else
            Array.from(draggables).forEach(item => item.draggable = true);

        //Adiciono los listeners a los eventos del draggable
        let i = 0;
        Array.from(draggables).forEach(item => {
            item.dataset.pos = ++i;
            this.initListeners(item);
        });
        this.container.addEventListener('dragover', this.dragOver.bind(this), false);
        this.container.addEventListener('dragenter', this.dragEnter.bind(this), false);
        this.container.addEventListener('drop', this.drop.bind(this), false);

    }

    initListeners(item) {
        item.addEventListener('dragstart', this.dragStart.bind(this), false);
        item.addEventListener('dragend', this.dragEnd.bind(this), false);
        item.addEventListener('dragging', this.dragging.bind(this), false);
        item.addEventListener('dragenter', this.dragEnter.bind(this), false);
        item.addEventListener('dragleave', this.dragLeave.bind(this), false);
        item.addEventListener('dragover', this.dragOver.bind(this), false);
        item.addEventListener('drop', this.drop.bind(this), false);
    }

    setDraggable(item) {
        if (this.handle) {
            const h = item.querySelector(this.handle);
            h.addEventListener('mousedown', this.handleEvents(item).down, false);
            h.addEventListener('mouseup', this.handleEvents(item).up, false);
        }
        else
            item.draggable = true;

        this.initListeners(item);
    }

    handleEvents(target) {
        return {
            up() {
                target.draggable = false;
            },
            down() {
                target.draggable = true;
            }
        }
    }

    dragStart(e) {
        e.stopPropagation();
        e.currentTarget.style.opacity = '0.4';
        this.el = e.currentTarget;
        this.from = this.tagName;
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/html', this.el.outerHTML);
        e.dataTransfer.setData('text/plain', JSON.stringify({
            uuid: this.uuid,
            group: this.group,
            from: this.container.tagName,
            //mas atributos para compartir
        }));
        if (this.floatingClass)
            e.currentTarget.classList.add(this.floatingClass);
        this.process(this.onDragStart)
    }

    dragOver(e) {
        if (e.preventDefault) {
            e.preventDefault(); // Necessary. Allows us to drop.
        }

        e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.

        if (this.overClass)
            e.currentTarget.classList.add(this.overClass);
        else
            e.currentTarget.style.background = "rgba(0,0,0,0.08)";

        this.el = e.currentTarget;
        this.onDragOver(this);
    }

    dragEnd(e) {
        e.currentTarget.style.opacity = '1';
        // this.setDraggable(e.currentTarget);
        this.container.style.background = 'transparent';
        this.onDragEnd(this);
    }

    dragEnter(e) {
        // e.currentTarget.style.border = 'dotted 1px'
        if (this.overClass) {
            e.currentTarget.classList.add(this.overClass);
            return
        }

        e.currentTarget.style.background = "rgba(0,0,0,0.08)"
    }

    dragLeave(e) {
        if (this.overClass) {
            e.currentTarget.classList.remove(this.overClass);
            return
        }

        e.currentTarget.style.background = 'transparent'
    }

    dragging() {
        this.onDragging(this);
    }

    process(callback) {
        return new Promise((resolve, reject) => {
            try {
                //Paso como parametro evt el contexto de sortable
                const result = callback(this);
                resolve(result)
            }
            catch (e) {
                console.log(e);
                reject(e)
            }
        })
    }

    drop(e) {
        // this/e.target is current target element.
        if (e.stopPropagation) {
            e.stopPropagation(); // Stops some browsers from redirecting.
        }
        const data = JSON.parse(e.dataTransfer.getData('text/plain'));

        //Creo el objeto el
        this.from = data.from;
        this.to = this.container.tagName;
        if (this.floatingClass)
            e.currentTarget.classList.remove(this.floatingClass);

        // Mismo Contenedor
        if (data.uuid === this.uuid) {
            if (this.sort && this.el !== e.currentTarget) {
                this.el.innerHTML = e.currentTarget.innerHTML;
                e.currentTarget.innerHTML = e.dataTransfer.getData('text/html');
                // TODO Mejorar la inicializacion de listeners para que no se dupliquen llamadas cuando esta activo el flag sort
                this.setDraggable(e.currentTarget);
                return
                // e.currentTarget.dataset.pos = e.dataTransfer.getData('text/plain');
            }

            this.el = e.currentTarget;
            //Espero por la ejecucion del callback que paso por parametro
            this.process(this.onDrop);
        }
        //Contenedores distintos de igual grupo
        else if (this.group === data.group) {
            const el = document.createElement(data.from);
            el.innerHTML = e.dataTransfer.getData('text/html');
            this.el = el.firstChild;
            //Espero por la ejecucion del callback que paso por parametro
            this.process(this.onDrop);
        }

        e.currentTarget.style.background = 'transparent';
        this.container.style.background = 'transparent';
        return false;
    }

    destroy() {
        this.mutationObserver().disconnect();
    }

    mutationObserver() {
        const _this = this;
        return new MutationObserver(function (mutations) {
            mutations.forEach(function ({addedNodes}) {
                if (addedNodes.length > 0)
                    addedNodes.forEach(node => _this.setDraggable(node))
            });
        });
    }
}