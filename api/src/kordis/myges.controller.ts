import {
  Body,
  Controller,
  Get,
  Param,
  ParseIntPipe,
  Post,
  Query,
} from '@nestjs/common';
import { KordisAuthService } from './kordis-auth.service';
import { MygesService } from './myges.service';
import { MygesCredentialsDto } from './dto/myges-credentials.dto';

@Controller('myges')
export class MygesController {
  constructor(
    private readonly kordisAuth: KordisAuthService,
    private readonly myges: MygesService,
  ) {}

  @Post('auth')
  async authenticate(@Body() credentials: MygesCredentialsDto) {
    const accessToken = await this.kordisAuth.authenticate(
      credentials.login,
      credentials.password,
    );
    return { accessToken };
  }

  @Get('profile')
  async getProfile(@Query('token') token: string) {
    return this.myges.getProfile(token);
  }

  @Get('agenda')
  async getAgenda(
    @Query('token') token: string,
    @Query('start') start: string,
    @Query('end') end: string,
  ) {
    return this.myges.getAgenda(token, start, end);
  }

  @Get('news')
  async getNews(@Query('token') token: string, @Query('page') page?: string) {
    return this.myges.getNews(token, page ? parseInt(page, 10) : 0);
  }

  @Get('news/banners')
  async getNewsBanners(@Query('token') token: string) {
    return this.myges.getNewsBanners(token);
  }

  @Get(':year/grades')
  async getGrades(
    @Query('token') token: string,
    @Param('year', ParseIntPipe) year: number,
  ) {
    return this.myges.getGrades(token, year);
  }

  @Get(':year/absences')
  async getAbsences(
    @Query('token') token: string,
    @Param('year', ParseIntPipe) year: number,
  ) {
    return this.myges.getAbsences(token, year);
  }

  @Get(':year/classes')
  async getClasses(
    @Query('token') token: string,
    @Param('year', ParseIntPipe) year: number,
  ) {
    return this.myges.getClasses(token, year);
  }

  @Get('classes/:classeId/students')
  async getStudents(
    @Query('token') token: string,
    @Param('classeId', ParseIntPipe) classeId: number,
  ) {
    return this.myges.getStudents(token, classeId);
  }

  @Get('students/:studentId')
  async getStudent(
    @Query('token') token: string,
    @Param('studentId', ParseIntPipe) studentId: number,
  ) {
    return this.myges.getStudent(token, studentId);
  }

  @Get(':year/teachers')
  async getTeachers(
    @Query('token') token: string,
    @Param('year', ParseIntPipe) year: number,
  ) {
    return this.myges.getTeachers(token, year);
  }

  @Get('teachers/:teacherId')
  async getTeacher(
    @Query('token') token: string,
    @Param('teacherId', ParseIntPipe) teacherId: number,
  ) {
    return this.myges.getTeacher(token, teacherId);
  }

  @Get(':year/courses')
  async getCourses(
    @Query('token') token: string,
    @Param('year', ParseIntPipe) year: number,
  ) {
    return this.myges.getCourses(token, year);
  }

  @Get('courses/:courseId/files')
  async getCourseDocuments(
    @Query('token') token: string,
    @Param('courseId', ParseIntPipe) courseId: number,
  ) {
    return this.myges.getCourseDocuments(token, courseId);
  }

  @Get(':year/projects')
  async getProjects(
    @Query('token') token: string,
    @Param('year', ParseIntPipe) year: number,
  ) {
    return this.myges.getProjects(token, year);
  }

  @Get(':year/practicals')
  async getPracticals(
    @Query('token') token: string,
    @Param('year', ParseIntPipe) year: number,
  ) {
    return this.myges.getPracticals(token, year);
  }

  @Get(':year/annual-documents')
  async getAnnualDocuments(
    @Query('token') token: string,
    @Param('year', ParseIntPipe) year: number,
  ) {
    return this.myges.getAnnualDocuments(token, year);
  }

  @Get('annual-documents/:documentId')
  async getAnnualDocument(
    @Query('token') token: string,
    @Param('documentId', ParseIntPipe) documentId: number,
  ) {
    return this.myges.getAnnualDocument(token, documentId);
  }
}
