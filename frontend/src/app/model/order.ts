import {Product} from "./product";

export class Order
{
  id:number;
  userId: number;
  productsResponseDtoArray: Product[];
  createdAt: string;
  address: string;
  statusName: string;
  methodName: string;
}
