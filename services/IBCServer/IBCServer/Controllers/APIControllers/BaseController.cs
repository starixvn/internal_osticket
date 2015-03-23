// --------------------------------------------------------------------------
// <copyright file="BaseController.cs" company="Nexcel Solutions Vietnam">
// Copyright (c) Nexcel Solutions Vietnam. All rights reserved.
// </copyright>
// --------------------------------------------------------------------------
// <history>
//   <change who="Edward.Nguyen" date="2013.9.16">Created</change>
// </history>

namespace IBCServer.Controllers.APIControllers
{
    using System.Web.Http;
    using IBCServer.DataAccess;

    public class BaseController : ApiController
    {
        public const int TotalItems = 100;

        public const int DefaultId = 0;

        public IBCDataAccess dataAccess;

        public BaseController()
        {
            dataAccess = new IBCDataAccess();
        }
    }
}