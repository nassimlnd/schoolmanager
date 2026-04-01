import { Injectable } from '@nestjs/common';
import { KordisAuthService } from './kordis-auth.service';
import type {
  MyGesAbsence,
  MyGesAgendaItem,
  MyGesApiResponse,
  MyGesClasse,
  MyGesCourse,
  MyGesGrade,
  MyGesNewsItem,
  MyGesProfile,
  MyGesProject,
  MyGesStudent,
  MyGesTeacher,
} from './interfaces/myges.interfaces';

@Injectable()
export class MygesService {
  private static readonly BASE_URL = 'https://api.kordis.fr/me';

  constructor(private readonly kordisAuth: KordisAuthService) {}

  async getProfile(accessToken: string): Promise<MyGesProfile> {
    return this.get<MyGesProfile>('/profile', accessToken);
  }

  async updateProfile(
    accessToken: string,
    fields: Partial<MyGesProfile>,
  ): Promise<MyGesProfile> {
    const current = await this.getProfile(accessToken);
    const updated = { ...current, ...fields };

    return this.put<MyGesProfile>('/profile', accessToken, updated);
  }

  async getAgenda(
    accessToken: string,
    start: string,
    end: string,
  ): Promise<MyGesAgendaItem[]> {
    const params = new URLSearchParams({ start, end });
    return this.get<MyGesAgendaItem[]>(`/agenda?${params}`, accessToken);
  }

  async getNews(accessToken: string, page = 0): Promise<MyGesNewsItem[]> {
    const params = new URLSearchParams({ page: String(page) });
    return this.get<MyGesNewsItem[]>(`/news?${params}`, accessToken);
  }

  async getNewsBanners(accessToken: string): Promise<MyGesNewsItem[]> {
    const response = await this.request<{ content: MyGesNewsItem[] }>(
      '/news/banners',
      accessToken,
    );
    return response.content;
  }

  async getGrades(accessToken: string, year: number): Promise<MyGesGrade[]> {
    return this.get<MyGesGrade[]>(`/${year}/grades`, accessToken);
  }

  async getAbsences(
    accessToken: string,
    year: number,
  ): Promise<MyGesAbsence[]> {
    return this.get<MyGesAbsence[]>(`/${year}/absences`, accessToken);
  }

  async getClasses(accessToken: string, year: number): Promise<MyGesClasse[]> {
    return this.get<MyGesClasse[]>(`/${year}/classes`, accessToken);
  }

  async getStudents(
    accessToken: string,
    classeId: number,
  ): Promise<MyGesStudent[]> {
    return this.get<MyGesStudent[]>(
      `/classes/${classeId}/students`,
      accessToken,
    );
  }

  async getStudent(
    accessToken: string,
    studentId: number,
  ): Promise<MyGesStudent> {
    return this.get<MyGesStudent>(`/students/${studentId}`, accessToken);
  }

  async getTeachers(
    accessToken: string,
    year: number,
  ): Promise<MyGesTeacher[]> {
    return this.get<MyGesTeacher[]>(`/${year}/teachers`, accessToken);
  }

  async getTeacher(
    accessToken: string,
    teacherId: number,
  ): Promise<MyGesTeacher> {
    return this.get<MyGesTeacher>(`/teachers/${teacherId}`, accessToken);
  }

  async getCourses(accessToken: string, year: number): Promise<MyGesCourse[]> {
    return this.get<MyGesCourse[]>(`/${year}/courses`, accessToken);
  }

  async getCourseDocuments(
    accessToken: string,
    courseId: number,
  ): Promise<unknown[]> {
    return this.get<unknown[]>(`/${courseId}/files`, accessToken);
  }

  async getProjects(
    accessToken: string,
    year: number,
  ): Promise<MyGesProject[]> {
    return this.get<MyGesProject[]>(`/${year}/projects`, accessToken);
  }

  async getPracticals(accessToken: string, year: number): Promise<unknown[]> {
    return this.get<unknown[]>(`/${year}/practicals`, accessToken);
  }

  async getAnnualDocuments(
    accessToken: string,
    year: number,
  ): Promise<unknown[]> {
    return this.get<unknown[]>(`/${year}/annualDocuments`, accessToken);
  }

  async getAnnualDocument(
    accessToken: string,
    documentId: number,
  ): Promise<unknown> {
    return this.get<unknown>(`/annualDocuments/${documentId}`, accessToken);
  }

  static getProjectFileUrl(projectStepFileId: number): string {
    return `${MygesService.BASE_URL}/projectStepFiles/${projectStepFileId}`;
  }

  private async get<T>(endpoint: string, accessToken: string): Promise<T> {
    return this.request<T>(endpoint, accessToken);
  }

  private async put<T>(
    endpoint: string,
    accessToken: string,
    body: unknown,
  ): Promise<T> {
    return this.request<T>(endpoint, accessToken, {
      method: 'PUT',
      body: JSON.stringify(body),
      headers: { 'Content-Type': 'application/json' },
    });
  }

  private async request<T>(
    endpoint: string,
    accessToken: string,
    options: RequestInit = {},
  ): Promise<T> {
    const url = `${MygesService.BASE_URL}${endpoint}`;

    const response = await fetch(url, {
      method: options.method ?? 'GET',
      headers: {
        Authorization: `Bearer ${accessToken}`,
        ...options.headers,
      },
      body: options.body,
    });

    if (!response.ok) {
      throw new Error(
        `MyGES API error: ${response.status} ${response.statusText}`,
      );
    }

    // eslint-disable-next-line @typescript-eslint/no-unsafe-assignment
    const data: MyGesApiResponse<T> = await response.json();
    return data.result;
  }
}
