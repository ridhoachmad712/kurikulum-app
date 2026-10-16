export default function curriculumMap(courses) {
    return {
        courses,
        search: '',
        concentration: '',
        type: '',
        cpl: '',
        selected: null,
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
            this.selected = course;
            this.$nextTick(() => this.$refs.detailClose?.focus());
        },

        close() {
            this.selected = null;
            this.$nextTick(() => this.returnFocus?.focus());
        },

        scrollMap(direction) {
            const map = this.$refs.desktopMap;
            if (!map) return;
            const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            map.scrollBy({ left: direction * 224 * 2, behavior: reducedMotion ? 'auto' : 'smooth' });
        },

        reset() {
            this.search = '';
            this.concentration = '';
            this.type = '';
            this.cpl = '';
        },

        courseClass(course) {
            if (course.elective) return 'border-amber-200 bg-amber-50 hover:border-amber-400';
            if (course.concentration) return 'border-violet-200 bg-violet-50 hover:border-violet-400';
            return 'border-blue-200 bg-white hover:border-blue-400';
        },

        courseType(course) {
            if (course.elective) return 'Pilihan';
            if (course.concentration) return 'Konsentrasi';
            return 'Umum';
        },
    };
}
