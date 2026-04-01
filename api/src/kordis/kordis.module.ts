import { Module } from '@nestjs/common';
import { KordisAuthService } from './kordis-auth.service';
import { MygesService } from './myges.service';
import { MygesController } from './myges.controller';

@Module({
  controllers: [MygesController],
  providers: [KordisAuthService, MygesService],
  exports: [KordisAuthService, MygesService],
})
export class KordisModule {}
