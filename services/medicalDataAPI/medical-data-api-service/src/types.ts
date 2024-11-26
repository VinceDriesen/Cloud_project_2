import { GraphQLResolveInfo } from 'graphql';
export type Maybe<T> = T | null;
export type InputMaybe<T> = Maybe<T>;
export type Exact<T extends { [key: string]: unknown }> = { [K in keyof T]: T[K] };
export type MakeOptional<T, K extends keyof T> = Omit<T, K> & { [SubKey in K]?: Maybe<T[SubKey]> };
export type MakeMaybe<T, K extends keyof T> = Omit<T, K> & { [SubKey in K]: Maybe<T[SubKey]> };
export type MakeEmpty<T extends { [key: string]: unknown }, K extends keyof T> = { [_ in K]?: never };
export type Incremental<T> = T | { [P in keyof T]?: P extends ' $fragmentName' | '__typename' ? T[P] : never };
export type RequireFields<T, K extends keyof T> = Omit<T, K> & { [P in K]-?: NonNullable<T[P]> };
/** All built-in and custom scalars, mapped to their actual values */
export type Scalars = {
  ID: { input: string; output: string; }
  String: { input: string; output: string; }
  Boolean: { input: boolean; output: boolean; }
  Int: { input: number; output: number; }
  Float: { input: number; output: number; }
};

export type DateRangeInput = {
  endDate?: InputMaybe<Scalars['String']['input']>;
  startDate?: InputMaybe<Scalars['String']['input']>;
};

export type Invoice = {
  __typename?: 'Invoice';
  amount: Scalars['Float']['output'];
  dateIssued: Scalars['String']['output'];
  id: Scalars['ID']['output'];
  status: InvoiceStatus;
  userId: Scalars['Int']['output'];
};

export type InvoiceInput = {
  amount: Scalars['Float']['input'];
  dateIssued: Scalars['String']['input'];
  status: InvoiceStatus;
};

export enum InvoiceStatus {
  Paid = 'PAID',
  Pending = 'PENDING',
  Unpaid = 'UNPAID'
}

export type LabResult = {
  __typename?: 'LabResult';
  date: Scalars['String']['output'];
  id: Scalars['ID']['output'];
  notes?: Maybe<Scalars['String']['output']>;
  referenceRange?: Maybe<Scalars['String']['output']>;
  resultValue: Scalars['String']['output'];
  testName: Scalars['String']['output'];
  unit?: Maybe<Scalars['String']['output']>;
  userId: Scalars['Int']['output'];
};

export type LabResultFilterInput = {
  dateRange?: InputMaybe<DateRangeInput>;
  testName?: InputMaybe<Scalars['String']['input']>;
};

export type LabResultInput = {
  date: Scalars['String']['input'];
  notes?: InputMaybe<Scalars['String']['input']>;
  referenceRange?: InputMaybe<Scalars['String']['input']>;
  resultValue: Scalars['String']['input'];
  testName: Scalars['String']['input'];
  unit?: InputMaybe<Scalars['String']['input']>;
};

export type MedicalImage = {
  __typename?: 'MedicalImage';
  dateUploaded: Scalars['String']['output'];
  description?: Maybe<Scalars['String']['output']>;
  id: Scalars['ID']['output'];
  imageUrl: Scalars['String']['output'];
  userId: Scalars['Int']['output'];
};

export type MedicalImageInput = {
  dateUploaded: Scalars['String']['input'];
  description?: InputMaybe<Scalars['String']['input']>;
  imageUrl: Scalars['String']['input'];
};

export type Mutation = {
  __typename?: 'Mutation';
  addInvoice?: Maybe<Invoice>;
  addLabResult?: Maybe<LabResult>;
  addVaccinationRecord?: Maybe<VaccinationRecord>;
  updateLabResult?: Maybe<LabResult>;
  uploadMedicalImage?: Maybe<MedicalImage>;
};


export type MutationAddInvoiceArgs = {
  input: InvoiceInput;
  userId: Scalars['Int']['input'];
};


export type MutationAddLabResultArgs = {
  input: LabResultInput;
  userId: Scalars['Int']['input'];
};


export type MutationAddVaccinationRecordArgs = {
  input: VaccinationInput;
  userId: Scalars['Int']['input'];
};


export type MutationUpdateLabResultArgs = {
  input: LabResultInput;
  resultId: Scalars['ID']['input'];
};


export type MutationUploadMedicalImageArgs = {
  input: MedicalImageInput;
  userId: Scalars['Int']['input'];
};

export type Query = {
  __typename?: 'Query';
  getInvoices?: Maybe<Array<Maybe<Invoice>>>;
  getLabResult?: Maybe<LabResult>;
  getLabResults?: Maybe<Array<Maybe<LabResult>>>;
  getMedicalImages?: Maybe<Array<Maybe<MedicalImage>>>;
  getVaccinationRecords?: Maybe<Array<Maybe<VaccinationRecord>>>;
};


export type QueryGetInvoicesArgs = {
  userId: Scalars['Int']['input'];
};


export type QueryGetLabResultArgs = {
  resultId: Scalars['ID']['input'];
};


export type QueryGetLabResultsArgs = {
  filter?: InputMaybe<LabResultFilterInput>;
  userId: Scalars['Int']['input'];
};


export type QueryGetMedicalImagesArgs = {
  userId: Scalars['Int']['input'];
};


export type QueryGetVaccinationRecordsArgs = {
  userId: Scalars['Int']['input'];
};

export type VaccinationInput = {
  administeredBy?: InputMaybe<Scalars['String']['input']>;
  dateAdministered: Scalars['String']['input'];
  notes?: InputMaybe<Scalars['String']['input']>;
  vaccineName: Scalars['String']['input'];
};

export type VaccinationRecord = {
  __typename?: 'VaccinationRecord';
  administeredBy?: Maybe<Scalars['String']['output']>;
  dateAdministered: Scalars['String']['output'];
  id: Scalars['ID']['output'];
  notes?: Maybe<Scalars['String']['output']>;
  userId: Scalars['Int']['output'];
  vaccineName: Scalars['String']['output'];
};



export type ResolverTypeWrapper<T> = Promise<T> | T;


export type ResolverWithResolve<TResult, TParent, TContext, TArgs> = {
  resolve: ResolverFn<TResult, TParent, TContext, TArgs>;
};
export type Resolver<TResult, TParent = {}, TContext = {}, TArgs = {}> = ResolverFn<TResult, TParent, TContext, TArgs> | ResolverWithResolve<TResult, TParent, TContext, TArgs>;

export type ResolverFn<TResult, TParent, TContext, TArgs> = (
  parent: TParent,
  args: TArgs,
  context: TContext,
  info: GraphQLResolveInfo
) => Promise<TResult> | TResult;

export type SubscriptionSubscribeFn<TResult, TParent, TContext, TArgs> = (
  parent: TParent,
  args: TArgs,
  context: TContext,
  info: GraphQLResolveInfo
) => AsyncIterable<TResult> | Promise<AsyncIterable<TResult>>;

export type SubscriptionResolveFn<TResult, TParent, TContext, TArgs> = (
  parent: TParent,
  args: TArgs,
  context: TContext,
  info: GraphQLResolveInfo
) => TResult | Promise<TResult>;

export interface SubscriptionSubscriberObject<TResult, TKey extends string, TParent, TContext, TArgs> {
  subscribe: SubscriptionSubscribeFn<{ [key in TKey]: TResult }, TParent, TContext, TArgs>;
  resolve?: SubscriptionResolveFn<TResult, { [key in TKey]: TResult }, TContext, TArgs>;
}

export interface SubscriptionResolverObject<TResult, TParent, TContext, TArgs> {
  subscribe: SubscriptionSubscribeFn<any, TParent, TContext, TArgs>;
  resolve: SubscriptionResolveFn<TResult, any, TContext, TArgs>;
}

export type SubscriptionObject<TResult, TKey extends string, TParent, TContext, TArgs> =
  | SubscriptionSubscriberObject<TResult, TKey, TParent, TContext, TArgs>
  | SubscriptionResolverObject<TResult, TParent, TContext, TArgs>;

export type SubscriptionResolver<TResult, TKey extends string, TParent = {}, TContext = {}, TArgs = {}> =
  | ((...args: any[]) => SubscriptionObject<TResult, TKey, TParent, TContext, TArgs>)
  | SubscriptionObject<TResult, TKey, TParent, TContext, TArgs>;

export type TypeResolveFn<TTypes, TParent = {}, TContext = {}> = (
  parent: TParent,
  context: TContext,
  info: GraphQLResolveInfo
) => Maybe<TTypes> | Promise<Maybe<TTypes>>;

export type IsTypeOfResolverFn<T = {}, TContext = {}> = (obj: T, context: TContext, info: GraphQLResolveInfo) => boolean | Promise<boolean>;

export type NextResolverFn<T> = () => Promise<T>;

export type DirectiveResolverFn<TResult = {}, TParent = {}, TContext = {}, TArgs = {}> = (
  next: NextResolverFn<TResult>,
  parent: TParent,
  args: TArgs,
  context: TContext,
  info: GraphQLResolveInfo
) => TResult | Promise<TResult>;



/** Mapping between all available schema types and the resolvers types */
export type ResolversTypes = {
  Boolean: ResolverTypeWrapper<Scalars['Boolean']['output']>;
  DateRangeInput: DateRangeInput;
  Float: ResolverTypeWrapper<Scalars['Float']['output']>;
  ID: ResolverTypeWrapper<Scalars['ID']['output']>;
  Int: ResolverTypeWrapper<Scalars['Int']['output']>;
  Invoice: ResolverTypeWrapper<Invoice>;
  InvoiceInput: InvoiceInput;
  InvoiceStatus: InvoiceStatus;
  LabResult: ResolverTypeWrapper<LabResult>;
  LabResultFilterInput: LabResultFilterInput;
  LabResultInput: LabResultInput;
  MedicalImage: ResolverTypeWrapper<MedicalImage>;
  MedicalImageInput: MedicalImageInput;
  Mutation: ResolverTypeWrapper<{}>;
  Query: ResolverTypeWrapper<{}>;
  String: ResolverTypeWrapper<Scalars['String']['output']>;
  VaccinationInput: VaccinationInput;
  VaccinationRecord: ResolverTypeWrapper<VaccinationRecord>;
};

/** Mapping between all available schema types and the resolvers parents */
export type ResolversParentTypes = {
  Boolean: Scalars['Boolean']['output'];
  DateRangeInput: DateRangeInput;
  Float: Scalars['Float']['output'];
  ID: Scalars['ID']['output'];
  Int: Scalars['Int']['output'];
  Invoice: Invoice;
  InvoiceInput: InvoiceInput;
  LabResult: LabResult;
  LabResultFilterInput: LabResultFilterInput;
  LabResultInput: LabResultInput;
  MedicalImage: MedicalImage;
  MedicalImageInput: MedicalImageInput;
  Mutation: {};
  Query: {};
  String: Scalars['String']['output'];
  VaccinationInput: VaccinationInput;
  VaccinationRecord: VaccinationRecord;
};

export type InvoiceResolvers<ContextType = any, ParentType extends ResolversParentTypes['Invoice'] = ResolversParentTypes['Invoice']> = {
  amount?: Resolver<ResolversTypes['Float'], ParentType, ContextType>;
  dateIssued?: Resolver<ResolversTypes['String'], ParentType, ContextType>;
  id?: Resolver<ResolversTypes['ID'], ParentType, ContextType>;
  status?: Resolver<ResolversTypes['InvoiceStatus'], ParentType, ContextType>;
  userId?: Resolver<ResolversTypes['Int'], ParentType, ContextType>;
  __isTypeOf?: IsTypeOfResolverFn<ParentType, ContextType>;
};

export type LabResultResolvers<ContextType = any, ParentType extends ResolversParentTypes['LabResult'] = ResolversParentTypes['LabResult']> = {
  date?: Resolver<ResolversTypes['String'], ParentType, ContextType>;
  id?: Resolver<ResolversTypes['ID'], ParentType, ContextType>;
  notes?: Resolver<Maybe<ResolversTypes['String']>, ParentType, ContextType>;
  referenceRange?: Resolver<Maybe<ResolversTypes['String']>, ParentType, ContextType>;
  resultValue?: Resolver<ResolversTypes['String'], ParentType, ContextType>;
  testName?: Resolver<ResolversTypes['String'], ParentType, ContextType>;
  unit?: Resolver<Maybe<ResolversTypes['String']>, ParentType, ContextType>;
  userId?: Resolver<ResolversTypes['Int'], ParentType, ContextType>;
  __isTypeOf?: IsTypeOfResolverFn<ParentType, ContextType>;
};

export type MedicalImageResolvers<ContextType = any, ParentType extends ResolversParentTypes['MedicalImage'] = ResolversParentTypes['MedicalImage']> = {
  dateUploaded?: Resolver<ResolversTypes['String'], ParentType, ContextType>;
  description?: Resolver<Maybe<ResolversTypes['String']>, ParentType, ContextType>;
  id?: Resolver<ResolversTypes['ID'], ParentType, ContextType>;
  imageUrl?: Resolver<ResolversTypes['String'], ParentType, ContextType>;
  userId?: Resolver<ResolversTypes['Int'], ParentType, ContextType>;
  __isTypeOf?: IsTypeOfResolverFn<ParentType, ContextType>;
};

export type MutationResolvers<ContextType = any, ParentType extends ResolversParentTypes['Mutation'] = ResolversParentTypes['Mutation']> = {
  addInvoice?: Resolver<Maybe<ResolversTypes['Invoice']>, ParentType, ContextType, RequireFields<MutationAddInvoiceArgs, 'input' | 'userId'>>;
  addLabResult?: Resolver<Maybe<ResolversTypes['LabResult']>, ParentType, ContextType, RequireFields<MutationAddLabResultArgs, 'input' | 'userId'>>;
  addVaccinationRecord?: Resolver<Maybe<ResolversTypes['VaccinationRecord']>, ParentType, ContextType, RequireFields<MutationAddVaccinationRecordArgs, 'input' | 'userId'>>;
  updateLabResult?: Resolver<Maybe<ResolversTypes['LabResult']>, ParentType, ContextType, RequireFields<MutationUpdateLabResultArgs, 'input' | 'resultId'>>;
  uploadMedicalImage?: Resolver<Maybe<ResolversTypes['MedicalImage']>, ParentType, ContextType, RequireFields<MutationUploadMedicalImageArgs, 'input' | 'userId'>>;
};

export type QueryResolvers<ContextType = any, ParentType extends ResolversParentTypes['Query'] = ResolversParentTypes['Query']> = {
  getInvoices?: Resolver<Maybe<Array<Maybe<ResolversTypes['Invoice']>>>, ParentType, ContextType, RequireFields<QueryGetInvoicesArgs, 'userId'>>;
  getLabResult?: Resolver<Maybe<ResolversTypes['LabResult']>, ParentType, ContextType, RequireFields<QueryGetLabResultArgs, 'resultId'>>;
  getLabResults?: Resolver<Maybe<Array<Maybe<ResolversTypes['LabResult']>>>, ParentType, ContextType, RequireFields<QueryGetLabResultsArgs, 'userId'>>;
  getMedicalImages?: Resolver<Maybe<Array<Maybe<ResolversTypes['MedicalImage']>>>, ParentType, ContextType, RequireFields<QueryGetMedicalImagesArgs, 'userId'>>;
  getVaccinationRecords?: Resolver<Maybe<Array<Maybe<ResolversTypes['VaccinationRecord']>>>, ParentType, ContextType, RequireFields<QueryGetVaccinationRecordsArgs, 'userId'>>;
};

export type VaccinationRecordResolvers<ContextType = any, ParentType extends ResolversParentTypes['VaccinationRecord'] = ResolversParentTypes['VaccinationRecord']> = {
  administeredBy?: Resolver<Maybe<ResolversTypes['String']>, ParentType, ContextType>;
  dateAdministered?: Resolver<ResolversTypes['String'], ParentType, ContextType>;
  id?: Resolver<ResolversTypes['ID'], ParentType, ContextType>;
  notes?: Resolver<Maybe<ResolversTypes['String']>, ParentType, ContextType>;
  userId?: Resolver<ResolversTypes['Int'], ParentType, ContextType>;
  vaccineName?: Resolver<ResolversTypes['String'], ParentType, ContextType>;
  __isTypeOf?: IsTypeOfResolverFn<ParentType, ContextType>;
};

export type Resolvers<ContextType = any> = {
  Invoice?: InvoiceResolvers<ContextType>;
  LabResult?: LabResultResolvers<ContextType>;
  MedicalImage?: MedicalImageResolvers<ContextType>;
  Mutation?: MutationResolvers<ContextType>;
  Query?: QueryResolvers<ContextType>;
  VaccinationRecord?: VaccinationRecordResolvers<ContextType>;
};

