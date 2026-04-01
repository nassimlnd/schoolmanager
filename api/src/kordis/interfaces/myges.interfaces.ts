export interface MyGesProfile {
  uid: number;
  firstname: string;
  lastname: string;
  email: string;
  personal_mail: string;
  birthday: number;
  ine: string;
  address1: string;
  address2: string;
  zipcode: string;
  city: string;
  country: string;
  phone: string;
  gender: string;
  level: string;
  program: string;
  links: MyGesLink[];
}

export interface MyGesLink {
  rel: string;
  href: string;
}

export interface MyGesAgendaItem {
  agenda_id: number;
  name: string;
  start_date: number;
  end_date: number;
  type: string;
  room: string;
  teacher: string;
  modality: string;
  discipline: string;
  comment: string;
}

export interface MyGesNewsItem {
  news_id: number;
  title: string;
  content: string;
  author: string;
  date: number;
  links: MyGesLink[];
}

export interface MyGesGrade {
  rc_id: number;
  trimester: number;
  trimester_name: string;
  year: number;
  letter_mark: string;
  grades: unknown[];
  lates: number;
  absences: number;
}

export interface MyGesAbsence {
  absence_id: number;
  date: number;
  course_name: string;
  justified: boolean;
  type: string;
}

export interface MyGesClasse {
  puid: number;
  name: string;
  school: string;
  year: number;
  trimester: number;
  promotion: string;
  description: string;
}

export interface MyGesStudent {
  uid: number;
  firstname: string;
  lastname: string;
  email: string;
  links: MyGesLink[];
}

export interface MyGesTeacher {
  uid: number;
  firstname: string;
  lastname: string;
  email: string;
  civility: string;
}

export interface MyGesCourse {
  rc_id: number;
  name: string;
  coef: number;
  ects: number;
  teacher_id: number;
}

export interface MyGesProjectStep {
  psp_id: number;
  psp_number: number;
  psp_desc: string;
  psp_limit_date: number;
  psp_type: string;
  files: MyGesProjectStepFile[] | null;
}

export interface MyGesProjectStepFile {
  psf_id: number;
  psf_name: string;
  psf_desc: string;
  psf_end_upload: number;
  psf_file_size: number;
  psf_file_hash: string;
  psf_file_type: string;
  pgr_id: number;
}

export interface MyGesProjectGroupStudent {
  u_id: number;
}

export interface MyGesProjectGroup {
  project_group_id: number;
  group_name: string;
  project_group_students: MyGesProjectGroupStudent[] | null;
}

export interface MyGesProjectLog {
  pgl_id: number;
  pgr_id: number;
  user_id: number;
  pgl_date: number;
  pgl_type_action: string;
  pgl_describe: string;
}

export interface MyGesProject {
  project_id: number;
  rc_id: number;
  teacher_id: number;
  name: string;
  project_detail_plan: string;
  project_teaching_goals: string;
  year: number;
  is_draft: boolean;
  project_create_date: number;
  update_user: string;
  update_date: number;
  groups: MyGesProjectGroup[];
  steps: MyGesProjectStep[] | null;
  project_group_logs: MyGesProjectLog[] | null;
}

export interface MyGesApiResponse<T> {
  result: T;
}
