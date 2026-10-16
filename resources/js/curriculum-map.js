export default function curriculumMap(courses, concentrations = []) {
    return {
        courses,
        concentrations,
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
            map.scrollBy({ left: direction * 200 * 2, behavior: reducedMotion ? 'auto' : 'smooth' });
        },

        reset() {
            this.search = '';
            this.concentration = '';
            this.type = '';
            this.cpl = '';
        },

        courseClass(course) {
            const concentrationColors = [
                'border-sky-400 bg-sky-100 hover:bg-sky-200',
                'border-amber-400 bg-amber-100 hover:bg-amber-200',
                'border-lime-500 bg-lime-100 hover:bg-lime-200',
                'border-violet-400 bg-violet-100 hover:bg-violet-200',
                'border-teal-400 bg-teal-100 hover:bg-teal-200',
            ];
            const index = this.concentrations.indexOf(course.concentration);
            const color = index < 0
                ? 'border-rose-400 bg-rose-100 hover:bg-rose-200'
                : concentrationColors[index % concentrationColors.length];

            return color + (course.elective ? ' border-dashed' : '');
        },

        courseType(course) {
            if (course.elective) return 'Pilihan';
            if (course.concentration) return 'Konsentrasi';
            return 'Umum';
        },
    };
}
