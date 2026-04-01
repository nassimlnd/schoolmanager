import { Module } from '@nestjs/common';
import { ConfigModule } from '@nestjs/config';
import { KordisModule } from './kordis/kordis.module';

@Module({
  imports: [ConfigModule.forRoot(), KordisModule],
  controllers: [],
  providers: [],
})
export class AppModule {}
