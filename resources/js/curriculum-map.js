export default function curriculumMap(courses, concentrations = []) {
    return {
        courses,
        concentrations,
        search: '',
        concentration: '',
        type: '',
        cpl: '',
        selected: null,
        hovered: null,
        previewX: 0,
        previewY: 0,
        expandedSemester: 1,
        returnFocus: null,

        matches(course) {
            const query = this.search.trim().toLocaleLowerCase('id');
            const matchesText = !query || [course.name, course.code].some(value =>
                value.toLocaleLowerCase('id').includes(query)
            );
            const matchesConcentration = !this.concentration ||
                !course.concentration ||
                course.concentration === this.concentration;
            const matchesType = !this.type ||
                (this.type === 'elective' && course.elective) ||
                (this.type === 'concentration' && course.concentration && !course.elective) ||
                (this.type === 'common' && !course.concentration && !course.elective);
            return matchesText && matchesConcentration && matchesType;
        },

        highlights(course) {
            return course.cpls.some(item => String(item.id) === this.cpl);
        },

        inSemester(semester) {
            return this.courses.filter(course => course.semester === semester && this.matches(course));
        },

        get overviewMode() {
            return !this.concentration && !this.search.trim() && !this.cpl;
        },

        displayedCourses(semester) {
            const courses = this.inSemester(semester);
            return this.overviewMode ? courses.filter(course => !course.concentration) : courses;
        },

        groupsInSemester(semester) {
            if (!this.overviewMode) return [];
            const courses = this.inSemester(semester);
            return this.concentrations.map(name => {
                const groupCourses = courses.filter(course => course.concentration === name);
                return {
                    name,
                    count: groupCourses.length,
                    sks: groupCourses.reduce((sum, course) => sum + Number(course.sks), 0),
                };
            }).filter(group => group.count > 0);
        },

        semesterSummary(semester) {
            const courses = this.inSemester(semester);
            const groups = this.groupsInSemester(semester);
            if (groups.length) return `${courses.length} MK tersedia · ${groups.length} konsentrasi`;
            const sks = courses.reduce((sum, course) => sum + Number(course.sks), 0);
            return `${courses.length} MK · ${sks} SKS tersedia`;
        },

        selectConcentration(name) {
            this.concentration = name;
            this.hovered = null;
        },

        get filteredCourses() {
            return this.courses.filter(course => this.matches(course));
        },

        get filteredSks() {
            return this.filteredCourses.reduce((sum, course) => sum + Number(course.sks), 0);
        },

        get highlightedCount() {
            return this.filteredCourses.filter(course => this.highlights(course)).length;
        },

        select(course) {
            this.returnFocus = document.activeElement;
            this.hovered = null;
            this.selected = course;
            this.$nextTick(() => this.$refs.detailClose?.focus());
        },

        close() {
            this.selected = null;
            this.$nextTick(() => this.returnFocus?.focus());
        },

        showPreview(event, course) {
            if (this.selected || window.matchMedia('(hover: none)').matches) return;
            this.hovered = course;
            this.movePreview(event);
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
        },

        scrollMap(direction) {
            const map = this.$refs.desktopMap;
            if (!map) return;
            const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            map.scrollBy({ left: direction * 200 * 2, behavior: reducedMotion ? 'auto' : 'smooth' });
        },

        reset() {
            this.search = '';
            this.concentration = '';
            this.type = '';
            this.cpl = '';
            this.hovered = null;
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
