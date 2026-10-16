export default function curriculumMap(courses, concentrations = [], cpls = []) {
    return {
        courses,
        concentrations,
        cpls,
        search: '',
        concentration: concentrations[0] || '',
        type: '',
        activeCpl: null,
        hoveredCpl: null,
        selected: null,
        hovered: null,
        previewX: 0,
        previewY: 0,
        expandedSemester: 1,
        returnFocus: null,
        linePaths: [],
        svgSize: { width: 0, height: 0 },
        resizeObserver: null,
        scrollHandler: null,
        frame: null,

        init() {
            for (const key of ['concentration', 'search', 'type', 'activeCpl']) {
                this.$watch(key, () => this.schedulePaths());
            }
            this.$nextTick(() => {
                if (this.$refs.diagram && window.ResizeObserver) {
                    this.resizeObserver = new ResizeObserver(() => this.schedulePaths());
                    this.resizeObserver.observe(this.$refs.diagram);
                }
                this.scrollHandler = () => {
                    if (this.focusedCourse || this.focusedCpl) this.schedulePaths();
                };
                window.addEventListener('scroll', this.scrollHandler, { passive: true });
                this.schedulePaths();
            });
        },

        destroy() {
            this.resizeObserver?.disconnect();
            if (this.scrollHandler) window.removeEventListener('scroll', this.scrollHandler);
            if (this.frame) cancelAnimationFrame(this.frame);
        },

        matches(course) {
            const query = this.search.trim().toLocaleLowerCase('id');
            const matchesText = !query || [course.name, course.code].some(value =>
                String(value || '').toLocaleLowerCase('id').includes(query)
            );
            const matchesConcentration = !course.concentration || course.concentration === this.concentration;
            const matchesType = !this.type ||
                (this.type === 'elective' && course.elective) ||
                (this.type === 'concentration' && course.concentration && !course.elective) ||
                (this.type === 'common' && !course.concentration && !course.elective);
            return matchesText && matchesConcentration && matchesType;
        },

        inSemester(semester) {
            return this.filteredCourses.filter(course => course.semester === semester);
        },

        semesterSummary(semester) {
            const courses = this.inSemester(semester);
            return `${courses.length} MK · ${courses.reduce((sum, course) => sum + Number(course.sks), 0)} SKS tersedia`;
        },

        get filteredCourses() {
            return this.courses.filter(course => this.matches(course));
        },

        get filteredSks() {
            return this.filteredCourses.reduce((sum, course) => sum + Number(course.sks), 0);
        },

        get focusedCourse() {
            return this.hovered || this.selected;
        },

        get focusedCpl() {
            return this.hoveredCpl || this.activeCpl;
        },

        get relatedCount() {
            return this.filteredCourses.filter(course => course.cpls.some(item => String(item.id) === String(this.focusedCpl))).length;
        },

        relatedToCpl(course, cplId = this.focusedCpl) {
            return course.cpls.some(item => String(item.id) === String(cplId));
        },

        isDimmed(course) {
            if (this.focusedCourse) return this.focusedCourse.id !== course.id;
            return this.focusedCpl && !this.relatedToCpl(course);
        },

        selectConcentration(name) {
            this.concentration = name;
            this.search = '';
            this.type = '';
            this.hovered = null;
            this.hoveredCpl = null;
            this.activeCpl = null;
            this.linePaths = [];
            this.schedulePaths();
        },

        toggleCpl(id) {
            this.activeCpl = String(this.activeCpl) === String(id) ? null : id;
            this.hovered = null;
            this.schedulePaths();
        },

        hoverCpl(id) {
            this.hoveredCpl = id;
            this.hovered = null;
            this.schedulePaths();
        },

        leaveCpl() {
            this.hoveredCpl = null;
            this.schedulePaths();
        },

        select(course) {
            this.returnFocus = document.activeElement;
            this.hovered = null;
            this.selected = course;
            this.schedulePaths();
            this.$nextTick(() => this.$refs.detailClose?.focus());
        },

        close() {
            this.selected = null;
            this.schedulePaths();
            this.$nextTick(() => this.returnFocus?.focus());
        },

        showPreview(event, course) {
            if (this.selected) return;
            this.hovered = course;
            if (!window.matchMedia('(hover: none)').matches) this.movePreview(event);
            this.schedulePaths();
        },

        movePreview(event) {
            if (!this.hovered) return;
            const rect = event.currentTarget.getBoundingClientRect();
            const x = event.clientX || rect.right;
            const y = event.clientY || rect.top;
            this.previewX = Math.max(12, Math.min(x + 18, window.innerWidth - 310));
            this.previewY = Math.max(12, Math.min(y + 18, window.innerHeight - 210));
        },

        hidePreview() {
            this.hovered = null;
            this.schedulePaths();
        },

        reset() {
            this.search = '';
            this.type = '';
            this.activeCpl = null;
            this.hoveredCpl = null;
            this.hovered = null;
            this.linePaths = [];
        },

        cplColor(id) {
            const palette = ['#be123c', '#e11d48', '#ea580c', '#d97706', '#ca8a04', '#16a34a', '#0d9488', '#0284c7', '#7c3aed', '#a21caf'];
            const index = this.cpls.findIndex(item => String(item.id) === String(id));
            return palette[Math.max(index, 0) % palette.length];
        },

        schedulePaths() {
            if (this.frame) cancelAnimationFrame(this.frame);
            this.frame = requestAnimationFrame(() => this.$nextTick(() => this.updatePaths()));
        },

        updatePaths() {
            this.frame = null;
            const diagram = this.$refs.diagram;
            if (!diagram || window.innerWidth < 768 || (!this.focusedCourse && !this.focusedCpl)) {
                this.linePaths = [];
                return;
            }

            const box = diagram.getBoundingClientRect();
            this.svgSize = { width: diagram.scrollWidth, height: diagram.scrollHeight };
            const courses = this.focusedCourse
                ? [this.focusedCourse].filter(course => this.matches(course))
                : this.filteredCourses.filter(course => this.relatedToCpl(course));
            const paths = [];

            for (const course of courses) {
                const courseNode = diagram.querySelector(`[data-map-course="${course.id}"]`);
                if (!courseNode) continue;
                const end = courseNode.getBoundingClientRect();
                for (const relation of course.cpls) {
                    if (!this.focusedCourse && String(relation.id) !== String(this.focusedCpl)) continue;
                    const cplNode = diagram.querySelector(`[data-map-cpl="${relation.id}"]`);
                    if (!cplNode) continue;
                    const start = cplNode.getBoundingClientRect();
                    const x1 = start.left + start.width / 2 - box.left;
                    const y1 = start.bottom - box.top;
                    const x2 = end.left + end.width / 2 - box.left;
                    const y2 = end.top - box.top;
                    const mid = y1 + Math.max(30, (y2 - y1) * .45);
                    paths.push({
                        key: `${course.id}-${relation.id}`,
                        color: this.cplColor(relation.id),
                        d: `M ${x1} ${y1} C ${x1} ${mid}, ${x2} ${mid}, ${x2} ${y2}`,
                    });
                }
            }
            this.linePaths = paths;
        },

        concentrationClass(name) {
            const colors = [
                'border-sky-400 bg-sky-100 hover:bg-sky-200',
                'border-amber-400 bg-amber-100 hover:bg-amber-200',
                'border-lime-500 bg-lime-100 hover:bg-lime-200',
                'border-violet-400 bg-violet-100 hover:bg-violet-200',
                'border-teal-400 bg-teal-100 hover:bg-teal-200',
            ];
            const index = this.concentrations.indexOf(name);
            return colors[Math.max(index, 0) % colors.length];
        },

        courseClass(course) {
            const color = course.concentration
                ? this.concentrationClass(course.concentration)
                : 'border-rose-400 bg-rose-100 hover:bg-rose-200';
            return color + (course.elective ? ' border-dashed' : '');
        },

        courseType(course) {
            if (course.elective) return 'Pilihan';
            if (course.concentration) return 'Konsentrasi';
            return 'Umum';
        },
    };
}
